<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Models\User;
use App\Services\TestService;
use App\Services\User\UserFilterService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PsychosocialMainController
{
    protected $testService;

    protected $filterService;

    protected $pageData;

    public function __construct(TestService $testService, UserFilterService $filterService)
    {
        $this->testService = $testService;
        $this->filterService = $filterService;
    }

    public function __invoke(Request $request)
    {
        Gate::authorize('view-manager-screens');

        // Catching users
        $query = session('company')->users()
            ->whereHas('latestPsychosocialCollection', function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            })
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->getQuery();

        // Applying filters
        $query = $query
            ->hasAttribute('name', 'like', "%$request->name%")
            ->hasAttribute('cpf', 'like', "%$request->cpf%")
            ->hasAttribute('gender', '=', $request->gender)
            ->hasAttribute('department', '=', $request->department)
            ->hasAttribute('occupation', '=', $request->occupation);

        $query = $this->filterService->applyAgeRange($query, $request->age_range);
        $query = $this->filterService->applyAdmissionRange($query, $request->admission_range);

        // Catching user tests
        $this->pageData = $query
            ->withLatestPsychosocialCollection(function ($query) use ($request) {
                $query->whereYear('created_at', $request->year ?? '2025')
                    ->withCollectionTypeName('psychosocial-risks')
                    ->withTests(function ($query) {
                        $query->withAnswersSum()
                            ->withAnswersCount()
                            ->withTestType(function ($q) {
                                $q->withRisks(function ($i) {
                                    $i->withRelatedQuestions()
                                        ->withControlActions();
                                });
                            });
                    });
            })
            ->get();

        $psychosocialRiskResults = $this->getCompiledPageData();
        $psychosocialTestsParticipation = $this->getPsychosocialTestsParticipation();

        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);
        $pendingTestUsers = session('company')->users->diff($this->pageData);

        return view('private.dashboard.psychosocial.index', [
            'psychosocialRiskResults' => $psychosocialRiskResults,
            'psychosocialTestsParticipation' => $psychosocialTestsParticipation,
            'filtersApplied' => $filtersApplied,
            'filteredUsers' => count($this->pageData) > 0 ? $this->pageData : null,
            'pendingTestUsers' => ! $filtersApplied ? $pendingTestUsers : null,
        ]);
    }

    private function getCompiledPageData()
    {
        $metrics = session('company')->metrics;
        $testCompiled = [];

        foreach ($this->pageData as $user) {
            if ($user->latestPsychosocialCollection) {
                $this->compileUserTests($user, $metrics, $testCompiled);
            }
        }

        $this->calculateAverageRiskScores($testCompiled);

        krsort($testCompiled);

        return $testCompiled;
    }

    private function compileUserTests(User $user, Collection $metrics, array &$testCompiled)
    {
        if ($user->latestPsychosocialCollection) {
            foreach ($user->latestPsychosocialCollection->tests as $userTest) {
                $testDisplayName = $userTest->testType->display_name;
                $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

                $this->updateTestSeverities($testDisplayName, $evaluatedTest, $testCompiled);

                if (isset($evaluatedTest['risks'])) {
                    $this->updateTestRisks($testDisplayName, $evaluatedTest['risks'], $testCompiled);
                }
            }
        }
    }

    private function updateTestSeverities(string $testDisplayName, array $evaluatedTest, array &$testCompiled)
    {
        if (! isset($testCompiled[$testDisplayName]['severities'][$evaluatedTest['severity_title']])) {
            $testCompiled[$testDisplayName]['severities'][$evaluatedTest['severity_title']] = [
                'count' => 0,
                'severity_color' => $evaluatedTest['severity_color'],
            ];
        }

        $testCompiled[$testDisplayName]['severities'][$evaluatedTest['severity_title']]['count'] += 1;
    }

    private function updateTestRisks(string $testDisplayName, array $risks, array &$testCompiled)
    {
        foreach ($risks as $riskName => $risk) {
            $testCompiled[$testDisplayName]['risks'][$riskName]['score'][] = $risk['riskPoints'];
        }
    }

    private function calculateAverageRiskScores(array &$testCompiled)
    {
        foreach ($testCompiled as $testName => $test) {
            if (isset($test['risks'])) {
                foreach ($test['risks'] as $riskName => $testRisk) {
                    $average = array_sum($testRisk['score']) / count($testRisk['score']);

                    $testCompiled[$testName]['risks'][$riskName]['score'] = ceil($average);
                    $this->determineRiskLevel($average, $testCompiled, $testName, $riskName);
                }
            }
        }
    }

    private function determineRiskLevel(float $average, array &$testCompiled, string $testName, string $riskName)
    {
        if ($average > 2) {
            $testCompiled[$testName]['risks'][$riskName]['risk'] = 'Risco Alto';
        } elseif ($average > 1) {
            $testCompiled[$testName]['risks'][$riskName]['risk'] = 'Risco Médio';
        } else {
            $testCompiled[$testName]['risks'][$riskName]['risk'] = 'Risco Baixo';
        }
    }

    private function getPsychosocialTestsParticipation()
    {
        $usersWithCollection = $this->pageData;
        $usersByDepartment = session('company')->users->groupBy('department');

        $participation = $this->calculateGeneralParticipation($usersWithCollection);
        $participation += $this->calculateDepartmentParticipation($usersWithCollection, $usersByDepartment);

        return $participation;
    }

    private function calculateGeneralParticipation($usersWithCollection)
    {
        return [
            'Geral' => [
                'Participação' => ($usersWithCollection->count() / session('company')->users->count()) * 100,
            ],
        ];
    }

    private function calculateDepartmentParticipation(Collection $usersWithCollection, Collection $usersByDepartment)
    {
        $departmentParticipation = [];

        foreach ($usersWithCollection->groupBy('department') as $departmentName => $department) {
            $countDepartmentUsers = $usersByDepartment[$departmentName]->count();

            $departmentParticipation[$departmentName] = [
                'Participação' => ($department->count() / $countDepartmentUsers) * 100,
            ];
        }

        return $departmentParticipation;
    }
}

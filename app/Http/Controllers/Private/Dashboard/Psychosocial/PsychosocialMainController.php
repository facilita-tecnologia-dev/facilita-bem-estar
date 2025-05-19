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
        Gate::authorize('psychosocial-dashboard-view');

        $this->pageData = $this->query($request);

        $psychosocialRiskResults = $this->getCompiledPageData();
        $psychosocialTestsParticipation = $this->getPsychosocialTestsParticipation();

        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);

        return view('private.dashboard.psychosocial.index', [
            'psychosocialRiskResults' => $psychosocialRiskResults,
            'psychosocialTestsParticipation' => $psychosocialTestsParticipation,
            'companyHasTests' => session('company')->users()->has('collections')->exists(),
            'filtersApplied' => $filtersApplied,
            'filteredUserCount' => count($this->pageData) > 0 ? count($this->pageData) : null,
        ]);
    }

    private function query(Request $request)
    {
        $query = session('company')->users()->getQuery();

        return $this->filterService->apply($query)
            ->whereHas('latestPsychosocialCollection', function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            })
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
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
        if ($user['latestPsychosocialCollection']) {
            foreach ($user['latestPsychosocialCollection']->tests as $userTest) {
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

        if (! $usersWithCollection->count()) {
            return null;
        }

        $participation = $this->calculateGeneralParticipation($usersWithCollection);
        $participation += $this->calculateDepartmentParticipation($usersWithCollection, $usersByDepartment);

        return $participation;
    }

    private function calculateGeneralParticipation($usersWithCollection)
    {
        return [
            'Geral' => [
                'count' => $usersWithCollection->count(),
                'per_cent' => ($usersWithCollection->count() / session('company')->users->count()) * 100,
            ],
        ];
    }

    private function calculateDepartmentParticipation(Collection $usersWithCollection, Collection $usersByDepartment)
    {
        $departmentParticipation = [];

        foreach ($usersByDepartment as $departmentName => $department) {
            $departmentParticipation[$departmentName] = [
                'count' => $usersWithCollection->where('department', $departmentName)->count(),
                'per_cent' => ($usersWithCollection->where('department', $departmentName)->count() / $department->count()) * 100,
            ];
        }

        return $departmentParticipation;
    }
}

<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Enums\AdmissionRangeEnum;
use App\Enums\AgeRangeEnum;
use App\Services\TestService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PsychosocialMainController
{
    protected $testService;

    protected $pageData;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function __invoke(Request $request)
    {
        Gate::authorize('view-manager-screens');

        $ageRange = $request->age_range ? AgeRangeEnum::from($request->age_range)->name : null;
        $admissionRange = $request->admission_range ? AdmissionRangeEnum::from($request->admission_range)->name : null;

        $this->pageData = $this->pageQuery(
            $request->name,
            $request->cpf,
            $request->department,
            $request->occupation,
            $request->gender,
            $request->work_shift,
            $request->marital_status,
            $request->education_level,
            $request->year,
            $ageRange,
            $admissionRange,
        );

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

    private function pageQuery(
        $filteredName = null,
        $filteredCPF = null, $filteredDepartment = null,
        $filteredOccupation = null,
        $filteredGender = null,
        $filteredWorkShift = null,
        $filteredMaritalStatus = null,
        $filteredEducationLevel = null,
        $filteredYear = null,
        $filteredAgeRange = null,
        $filteredAdmissionRange = null,
    ) {
        $pageData = session('company')
            ->users()
            ->whereHas('latestPsychosocialCollection', function ($query) use ($filteredYear) {
                $query->whereYear('created_at', $filteredYear ?? Carbon::now()->year);
            })
            ->hasAttribute('name', 'like', "%$filteredName%")
            ->hasAttribute('cpf', 'like', "%$filteredCPF%")
            ->hasAttribute('gender', '=', $filteredGender)
            ->hasAttribute('department', '=', $filteredDepartment)
            ->hasAttribute('occupation', '=', $filteredOccupation)
            ->hasAttribute('work_shift', '=', $filteredWorkShift)
            ->hasAttribute('marital_status', '=', $filteredMaritalStatus)
            ->hasAttribute('education_level', '=', $filteredEducationLevel)
            ->hasAgeRange($filteredAgeRange)
            ->hasAdmissionRange($filteredAdmissionRange)
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->withLatestPsychosocialCollection(year: $filteredYear)
            ->get();

        return $pageData;
    }

    private function getCompiledPageData()
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->pageData as $user) {
            if ($user->latestPsychosocialCollection && count($user->latestPsychosocialCollection->tests) > 0) {
                foreach ($user->latestPsychosocialCollection->tests as $userTest) {
                    $testDisplayName = $userTest->testType->display_name;
                    $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

                    if (! isset($testCompiled[$testDisplayName]['severities'][$evaluatedTest['severity_title']])) {
                        $testCompiled[$testDisplayName]['severities'][$evaluatedTest['severity_title']] = [
                            'count' => 0,
                            'severity_color' => $evaluatedTest['severity_color'],
                        ];
                    }

                    if (isset($evaluatedTest['risks'])) {
                        foreach ($evaluatedTest['risks'] as $riskName => $risk) {
                            $testCompiled[$testDisplayName]['risks'][$riskName]['score'][] = $risk['riskPoints'];
                        }
                    }

                    $testCompiled[$testDisplayName]['severities'][$evaluatedTest['severity_title']]['count'] += 1;
                }
            }
        }

        foreach ($testCompiled as $testName => $test) {
            if (isset($test['risks'])) {
                foreach ($test['risks'] as $riskName => $testRisk) {
                    $average = array_sum($testRisk['score']) / count($testRisk['score']);
                    $testCompiled[$testName]['risks'][$riskName]['score'] = ceil($average);

                    if ($average > 2) {
                        $testCompiled[$testName]['risks'][$riskName]['risk'] = 'Risco Alto';
                    } elseif ($average > 1) {
                        $testCompiled[$testName]['risks'][$riskName]['risk'] = 'Risco Médio';
                    } else {
                        $testCompiled[$testName]['risks'][$riskName]['risk'] = 'Risco Baixo';
                    }
                }
            }
        }

        krsort($testCompiled);

        return $testCompiled;
    }

    private function getPsychosocialTestsParticipation()
    {
        $usersWithCollection = $this->pageData->filter(function ($user) {
            return $user->has('latestPsychosocialCollection');
        });

        $companyUsersByDepartment = session('company')->users->groupBy('department');

        $psychosocialTestsParticipation = [];

        $psychosocialTestsParticipation['Geral'] = [
            'Participação' => ($usersWithCollection->count() / session('company')->users->count()) * 100,
        ];

        foreach ($usersWithCollection->groupBy('department') as $departmentName => $department) {
            $countDepartmentUsers = $companyUsersByDepartment[$departmentName]->count();

            $departmentParticipation = [
                'Participação' => ($department->count() / $countDepartmentUsers) * 100,
            ];

            $psychosocialTestsParticipation[$departmentName] = $departmentParticipation;
        }

        return $psychosocialTestsParticipation;
    }
}

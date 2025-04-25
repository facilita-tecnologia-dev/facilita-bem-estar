<?php

namespace App\Http\Controllers\Private\Dashboard\Organizational;

use App\Enums\AdmissionRangeEnum;
use App\Enums\AgeRangeEnum;
use App\Services\TestService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrganizationalMainController
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

        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);

        $organizationalClimateResults = $this->getCompiledPageData($filtersApplied);
        $organizationalTestsParticipation = $this->getOrganizationalTestsParticipation();

        $pendingTestUsers = session('company')->users->diff($this->pageData);

        return view('private.dashboard.organizational.index', [
            'organizationalClimateResults' => $organizationalClimateResults,
            'organizationalTestsParticipation' => $organizationalTestsParticipation,
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
            ->whereHas('latestOrganizationalClimateCollection', function ($query) use ($filteredYear) {
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
            ->select('users.id', 'users.name', 'users.department', 'users.gender', 'users.admission', 'users.birth_date')
            ->withLatestOrganizationalClimateCollection(year: $filteredYear)
            ->get();

        return $pageData;
    }

    private function getCompiledPageData(array $filtersApplied)
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->pageData as $user) {
            foreach ($user->latestOrganizationalClimateCollection->tests as $userTest) {

                $testDisplayName = $userTest->testType->display_name;

                $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

                foreach ($evaluatedTest['processed_answers'] as $questionNumber => $answer) {
                    if (! array_key_exists('department', $filtersApplied) && ! array_key_exists('occupation', $filtersApplied)) {
                        $testCompiled[$testDisplayName]['Geral']['answers'][$questionNumber][] = $answer;
                    }
                    $testCompiled[$testDisplayName][$user->department]['answers'][$questionNumber][] = $answer;
                }
            }
        }

        foreach ($testCompiled as $testName => $test) {
            foreach ($test as $categoryName => $category) {
                foreach ($category['answers'] as $questionNumber => $answers) {
                    $count = count($answers);
                    $sum = array_sum($answers);
                    $testCompiled[$testName][$categoryName]['total_average'] = $sum / $count;
                }
            }
        }

        krsort($testCompiled);

        return $testCompiled;
    }

    private function getOrganizationalTestsParticipation()
    {
        $usersByDepartment = $this->pageData->filter(function ($user) {
            return $user->has('latestOrganizationalClimateCollection');
        });

        $companyUsersByDepartment = session('company')->users->groupBy('department');

        $organizationalTestsParticipation = [];

        $organizationalTestsParticipation['Geral'] = [
            'Participação' => ($usersByDepartment->count() / session('company')->users->count()) * 100,
        ];

        foreach ($usersByDepartment->groupBy('department') as $departmentName => $department) {
            $countDepartmentUsers = $companyUsersByDepartment[$departmentName]->count();
            $departmentParticipation = [
                'Participação' => ($department->count() / $countDepartmentUsers) * 100,
            ];

            $organizationalTestsParticipation[$departmentName] = $departmentParticipation;
        }

        return $organizationalTestsParticipation;
    }
}

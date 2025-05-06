<?php

namespace App\Http\Controllers\Private\Dashboard\Organizational;

use App\Enums\AdmissionRangeEnum;
use App\Enums\AgeRangeEnum;
use App\Models\User;
use App\Services\TestService;
use App\Services\User\UserFilterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrganizationalMainController
{
    protected $testService;

    protected $filterService;

    protected $pageData;

    public function __construct(TestService $testService, UserFilterService $filterService,)
    {
        $this->testService = $testService;
        $this->filterService = $filterService;
    }

    public function __invoke(Request $request)
    {
        Gate::authorize('view-manager-screens');
        
        $this->pageData = $this->query($request);

        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);

        $organizationalClimateResults = $this->getCompiledPageData($filtersApplied);
        $organizationalTestsParticipation = $this->getOrganizationalTestsParticipation();

        return view('private.dashboard.organizational.index', [
            'organizationalClimateResults' => $organizationalClimateResults,
            'organizationalTestsParticipation' => $organizationalTestsParticipation,
            'filtersApplied' => $filtersApplied,
            'filteredUserCount' => count($this->pageData) > 0 ? count($this->pageData) : null,
        ]);
    }

    private function query(Request $request){
        $query = session('company')->users()
        ->getQuery();

        return $this->filterService->apply($query)
            ->whereHas('latestOrganizationalClimateCollection', function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            })
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->withLatestOrganizationalClimateCollection(function ($query) use ($request) {
                $query->whereYear('created_at', $request->year ?? '2025')
                    ->withCollectionTypeName('organizational-climate')
                    ->withTests(function ($query) {
                        $query
                            ->withAnswers()
                            ->withTestType();
                    });
            })
            ->get();
    }

    public function createPDFReport(Request $request){
        $filtersApplied = [];

        foreach($request->query() as $filterKeyName => $filter){
            $filtersApplied[$this->filterService->getFilterDisplayName($filterKeyName)] = $filter;
        }

        $charts = [];
        
        foreach($request->all() as $chartName => $chartToBase64){
            if(str_contains($chartName, '-to-base-64')){
                $chartName = str_replace('_', ' ', str_replace('-to-base-64', '', $chartName));
                $charts[$chartName] = $chartToBase64;
            }
        }

 
        $company = session('company');

        $companyLogo = $company->logo;
        $companyName = $company->name;

        $pdf = Pdf::loadView('pdf.organizational-climate-index', [
            'companyLogo' => $companyLogo,
            'companyName' => $companyName,
            'charts' => $charts,
            'filtersApplied' => $filtersApplied,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('graficos_clima_organizacional.pdf');
    }

    private function getCompiledPageData(array $filtersApplied)
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->pageData as $user) {
            if ($user->latestOrganizationalClimateCollection) {
                $this->compileUserTests($user, $metrics, $testCompiled, $filtersApplied);
            }
        }

        $this->calculateAverageScore($testCompiled);

        krsort($testCompiled);

        return $testCompiled;
    }

    private function compileUserTests(User $user, Collection $metrics, &$testCompiled, array $filtersApplied)
    {
        foreach ($user['latestOrganizationalClimateCollection']['tests'] as $userTest) {
            $testDisplayName = $userTest->testType->display_name;

            $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

            $this->updateAnswers($testDisplayName, $evaluatedTest, $testCompiled, $user, $filtersApplied);
        }
    }

    private function updateAnswers(string $testDisplayName, array $evaluatedTest, array &$testCompiled, User $user, array $filtersApplied)
    {
        foreach ($evaluatedTest['processed_answers'] as $questionNumber => $answer) {
            if (! array_key_exists('department', $filtersApplied) && ! array_key_exists('occupation', $filtersApplied)) {
                $testCompiled[$testDisplayName]['Geral']['answers'][$questionNumber][] = $answer;
            }
            $testCompiled[$testDisplayName][$user->department]['answers'][$questionNumber][] = $answer;
        }
    }

    private function calculateAverageScore(&$testCompiled)
    {
        foreach ($testCompiled as $testName => $test) {
            foreach ($test as $categoryName => $category) {
                foreach ($category['answers'] as $questionNumber => $answers) {
                    $count = count($answers);
                    $sum = array_sum($answers);
                    $testCompiled[$testName][$categoryName]['total_average'][] = $sum / $count;
                }
                $testCompiled[$testName][$categoryName]['total_average'] = array_sum($testCompiled[$testName][$categoryName]['total_average']) / count($testCompiled[$testName][$categoryName]['total_average']);
            }
        }
    }

    private function getOrganizationalTestsParticipation()
    {
        $usersWithCollection = $this->pageData;
        $usersByDepartment = session('company')->users->groupBy('department');

        $participation = $this->calculateGeneralParticipation($usersWithCollection);
        $participation += $this->calculateDepartmentParticipation($usersWithCollection, $usersByDepartment);

        return $participation;
    }

    private function calculateGeneralParticipation(Collection $usersWithCollection)
    {
        return [
            'Geral' => [
                'count' => $usersWithCollection->count(),
                'per_cent' => ($usersWithCollection->count() / session('company')->users->count()) * 100
            ],
        ];
    }

    private function calculateDepartmentParticipation(Collection $usersWithCollection, Collection $companyUsersByDepartment)
    {
        $departmentParticipation = [];

        foreach ($usersWithCollection->groupBy('department') as $departmentName => $department) {
            $countDepartmentUsers = $companyUsersByDepartment[$departmentName]->count();

            $departmentParticipation[$departmentName] = [
                'count' => $department->count(),
                'per_cent' => ($department->count() / $countDepartmentUsers) * 100,
            ];
        }

        return $departmentParticipation;
    }
}

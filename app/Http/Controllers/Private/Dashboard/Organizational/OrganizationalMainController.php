<?php

namespace App\Http\Controllers\Private\Dashboard\Organizational;

use App\Helpers\AuthGuardHelper;
use App\Models\Collection;
use App\Models\Company;
use App\Models\CustomCollection;
use App\Models\CustomTest;
use App\Models\Test;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserCollection;
use App\Models\UserCustomTest;
use App\Repositories\TestRepository;
use App\Services\Dashboard\OrganizationalClimateService;
use App\Services\TestService;
use App\Services\User\UserFilterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrganizationalMainController
{
    protected $testService;

    protected $filterService;

    protected $organizationalClimateService;

    protected $pageData;

    protected $generalTestResults;

    protected $scopedTestResults;

    public function __construct(TestService $testService, UserFilterService $filterService, OrganizationalClimateService $organizacionalClimateService)
    {
        $this->testService = $testService;
        $this->filterService = $filterService;
        $this->organizationalClimateService = $organizacionalClimateService;
    }

    public function __invoke(Request $request)
    {
        Gate::authorize('organizational-dashboard-view');

        $this->pageData = $this->query();
        $this->generalTestResults = $this->organizationalClimateService->getOrganizationalData($request, true);
        $this->scopedTestResults = $this->organizationalClimateService->filterByDepartmentScope($this->pageData);

        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);
        
        $organizationalClimateResults = $this->getCompiledPageData($filtersApplied);

        $organizationalTestsParticipation = $this->getOrganizationalTestsParticipation();

        return view('private.dashboard.organizational.index', [
            'organizationalClimateResults' => $organizationalClimateResults,
            'organizationalTestsParticipation' => $organizationalTestsParticipation,
            'companyHasTests' => session('company')->users()->has('collections')->exists(),
            'filtersApplied' => $filtersApplied,
            'filteredUserCount' => count($this->scopedTestResults) > 0 ? count($this->scopedTestResults) : null,
        ]);
    }

    private function query()
    {
        $companyUserIds = session('company')->users->pluck('id');        
        $query = User::whereIn('id', $companyUserIds);

        return $this->filterService->apply($query)
            ->select('id', 'department')
            ->whereHas('latestOrganizationalClimateCollection')
            ->with(['latestOrganizationalClimateCollection' => function($query){
                $query->with(['collectionType.tests.questions', 'tests' => fn($q) => $q->with('answers', 'testType')]);
            }])
            ->get();
    }

    public function createPDFReport(Request $request)
    {
        Gate::authorize('organizational-dashboard-view');

        $filtersApplied = [];

        $isUser = AuthGuardHelper::user() instanceof User;
        $userDepartmentScopes = $isUser ? AuthGuardHelper::user()->departmentScopes->where('allowed', 1) : false;

        foreach ($request->query() as $filterKeyName => $filter) {
            $filtersApplied[$this->filterService->getFilterDisplayName($filterKeyName)] = $filter;
        }

        $charts = [];

        foreach ($request->all() as $chartName => $chartToBase64) {
            if (str_contains($chartName, '-to-base-64')) {
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
            'userDepartmentScopes' => $userDepartmentScopes,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('graficos_clima_organizacional.pdf');
    }

    private function getCompiledPageData(array $filtersApplied)
    {   
        $testCompiled = [];

        $isUser = AuthGuardHelper::user() instanceof User;
        $userDepartmentScopes = $isUser ? AuthGuardHelper::user()->departmentScopes->where('allowed', 1) : false;

        foreach($this->pageData as $user){
            if ($user->latestOrganizationalClimateCollection && (!$isUser || $userDepartmentScopes->where('department', $user->department)->count())) {
                $this->compileUserTests($user, $testCompiled, $filtersApplied);
            }
        }

        $this->calculateAverageScore($testCompiled);
        
        krsort($testCompiled);

        // General (If have department scopes)
        if($userDepartmentScopes){
            $testGeneralCompiled = [];

            foreach($this->pageData as $user){
                if ($user->latestOrganizationalClimateCollection) {
                    $this->compileUserTests($user, $testGeneralCompiled, $filtersApplied, true);
                }
            }
            
            $this->calculateAverageScore($testGeneralCompiled);

            krsort($testGeneralCompiled);

            $testCompiled = ['main' => $testCompiled, 'general' => $testGeneralCompiled];
        } else{
            $testCompiled = ['main' => $testCompiled];
        }

        return $testCompiled;
    }

    private function compileUserTests(User $user, &$testCompiled, array $filtersApplied, $onlyGeneral = false)
    {
        $tests = $user['latestOrganizationalClimateCollection']['tests'];

        $tests->each(function($userTest) use($user, &$testCompiled, $filtersApplied, $onlyGeneral) {
            $testDisplayName = $userTest['testType']['display_name'];
            $evaluatedTest = $this->testService->evaluateTest($userTest['testType'], $userTest, session('company')->metrics, 'organizational-climate');
            $this->updateAnswers($testDisplayName, $evaluatedTest, $testCompiled, $user, $filtersApplied, $onlyGeneral);
        }); 
    }

    private function updateAnswers(string $testDisplayName, array $evaluatedTest, array &$testCompiled, User $user, array $filtersApplied, $onlyGeneral = false)
    {
        foreach ($evaluatedTest['processed_answers'] as $questionNumber => $answer) {
            if($onlyGeneral){
                $testCompiled[$testDisplayName]['Geral da empresa']['answers'][$questionNumber][] = $answer;
            } else{
                if (! array_key_exists('department', $filtersApplied) && ! array_key_exists('occupation', $filtersApplied)) {
                    $testCompiled[$testDisplayName]['Geral']['answers'][$questionNumber][] = $answer;
                }

                $testCompiled[$testDisplayName][$user->department]['answers'][$questionNumber][] = $answer;
            }
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
        $usersWithCollection = $this->scopedTestResults;
        $usersByDepartment = $this->organizationalClimateService->filterByDepartmentScope(session('company')->users)->groupBy('department');
        
        if (! $usersWithCollection->count()) {
            return null;
        }

        $participation = $this->calculateGeneralParticipation($usersWithCollection);
        $participation += $this->calculateDepartmentParticipation($usersWithCollection, $usersByDepartment);

        return $participation;
    }

    private function calculateGeneralParticipation(EloquentCollection $usersWithCollection)
    {
        return [
            'Geral' => [
                'count' => $usersWithCollection->count(),
                'per_cent' => ($usersWithCollection->count() / session('company')->users->count()) * 100,
            ],
        ];
    }

    private function calculateDepartmentParticipation(EloquentCollection $usersWithCollection, EloquentCollection $companyUsersByDepartment)
    {
        $departmentParticipation = [];
        
        foreach ($companyUsersByDepartment as $departmentName => $department) {
            $departmentUsersWithCollection = $usersWithCollection->where('department', $departmentName)->count();

            // if($departmentUsersWithCollection){
                $departmentParticipation[$departmentName] = [
                    'count' => $departmentUsersWithCollection,
                    'per_cent' => ($departmentUsersWithCollection / $department->count()) * 100,
                ];
            // }
        }


        return $departmentParticipation;
    }
}

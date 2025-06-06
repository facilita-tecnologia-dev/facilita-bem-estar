<?php

namespace App\Http\Controllers\Private\Dashboard\Organizational;

use App\Models\Collection;
use App\Models\Company;
use App\Models\CustomTest;
use App\Models\Test;
use App\Models\User;
use App\Models\UserCustomTest;
use App\Repositories\TestRepository;
use App\Services\TestService;
use App\Services\User\UserFilterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrganizationalMainController
{
    protected $testService;

    protected $filterService;

    protected $pageData;

    protected $companyCustomTests;

    protected $defaultTests;

    public function __construct(TestService $testService, UserFilterService $filterService)
    {
        $this->testService = $testService;
        $this->filterService = $filterService;

        $this->companyCustomTests = TestRepository::companyCustomTests();
        $this->defaultTests = TestRepository::defaultTests();
    }

    public function __invoke(Request $request)
    {
        Gate::authorize('organizational-dashboard-view');

        $this->pageData = $this->query($request);
        
        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);
        
        $organizationalClimateResults = $this->getCompiledPageData($filtersApplied);
        $organizationalTestsParticipation = $this->getOrganizationalTestsParticipation();

        return view('private.dashboard.organizational.index', [
            'organizationalClimateResults' => $organizationalClimateResults,
            'organizationalTestsParticipation' => $organizationalTestsParticipation,
            'companyHasTests' => session('company')->users()->has('collections')->exists(),
            'filtersApplied' => $filtersApplied,
            'filteredUserCount' => count($this->pageData) > 0 ? count($this->pageData) : null,
        ]);
    }

    private function query(Request $request)
    {
        $query = session('company')->users()
            ->getQuery();

        return $this->filterService->apply($query)
            ->whereHas('latestOrganizationalClimateCollection')
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->withLatestOrganizationalClimateCollection(function ($query) use ($request) {
                $query->whereYear('created_at', $request->year ?? now()->year)
                    ->withCollectionTypeName('organizational-climate')
                    ->withTests(function ($query) {
                        $query
                            ->withAnswers()
                            ->withTestType();
                    })
                    ->withCustomTests(function($query) {
                        $query
                            ->withAnswers()
                            ->withCustomTestType();
                    });
            })
            ->get();
    }

    public function createPDFReport(Request $request)
    {
        Gate::authorize('organizational-dashboard-view');

        $filtersApplied = [];

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

    private function compileUserTests(User $user, EloquentCollection $metrics, &$testCompiled, array $filtersApplied)
    {
        if($user->latestOrganizationalClimateCollection){
            $defaultTests = $user->latestOrganizationalClimateCollection->tests->map(function($defaultTest) use($user) {
                $relatedCustomTest = $user->latestOrganizationalClimateCollection->customTests
                ->first(fn($userCustomTest) => $userCustomTest->relatedCustomTest->test_id == $defaultTest->test_id);

                if($relatedCustomTest){
                    $mergedAnswers = $defaultTest['answers']->merge($relatedCustomTest->answers);
                
                    $defaultTest->setRelation('answers', $mergedAnswers);
                }

                return $defaultTest;
            });
            
            $customTests = $user->latestOrganizationalClimateCollection->customTests
            ->filter(function($customTest){
                return !$customTest->relatedCustomTest->test_id;
            });
            
            $mergedUserTests = $defaultTests->merge($customTests);


            $mergedUserTests->each(function($userTest) use($metrics, $user, &$testCompiled, $filtersApplied) {
                $testDisplayName = $userTest instanceof UserCustomTest ?
                                    $userTest->relatedCustomTest->display_name :
                                    $userTest->testType->display_name;
                
                $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics, $user->latestOrganizationalClimateCollection['collection_type_name']);

                $this->updateAnswers($testDisplayName, $evaluatedTest, $testCompiled, $user, $filtersApplied);
            }); 
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
            $departmentParticipation[$departmentName] = [
                'count' => $usersWithCollection->where('department', $departmentName)->count(),
                'per_cent' => ($usersWithCollection->where('department', $departmentName)->count() / $department->count()) * 100,
            ];
        }

        return $departmentParticipation;
    }
}

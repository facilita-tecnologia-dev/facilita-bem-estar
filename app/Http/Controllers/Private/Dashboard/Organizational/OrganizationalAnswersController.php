<?php

namespace App\Http\Controllers\Private\Dashboard\Organizational;

use App\Models\User;
use App\Models\UserCustomAnswer;
use App\Models\UserCustomTest;
use App\Repositories\TestRepository;
use App\Services\TestService;
use App\Services\User\UserFilterService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrganizationalAnswersController
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

        $organizationalClimateResults = $this->getCompiledPageData($request);

        return view('private.dashboard.organizational.by-answers', compact(
            'organizationalClimateResults',
        ));
    }

    private function query($request)
    {
        // Catching users
        $query = session('company')->users()
            ->getQuery();
            
        return $this->filterService->apply($query)
            ->whereHas('latestOrganizationalClimateCollection')
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->withLatestOrganizationalClimateCollection(function ($query) use ($request) {
                $query->whereYear('created_at', $request->year ?? now()->year)
                    ->withCollectionTypeName('organizational-climate')
                    ->withTests(function ($query) use ($request) {
                        $query
                            ->withAnswers()
                            ->withTestType();
                    })
                    ->withCustomTests(function($query) use ($request) {
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

        $company = session('company');

        $this->pageData = $this->query($request);

        $organizationalClimateResults = $this->getCompiledPageData($request);
        $companyLogo = $company->logo;
        $companyName = $company->name;

        $pdf = Pdf::loadView('pdf.organizational-climate-answers', [
            'companyLogo' => $companyLogo,
            'companyName' => $companyName,
            'organizationalClimateResults' => $organizationalClimateResults,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('graficos_clima_organizacional.pdf');
    }

    private function getCompiledPageData(Request $request)
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->pageData as $user) {
            if ($user->latestOrganizationalClimateCollection) {
                $this->compileUserResults($user, $metrics, $testCompiled, $request);
            }
        }

        $this->calculateAverageScoreByQuestion($testCompiled);

        krsort($testCompiled);

        return $testCompiled;
    }

    private function compileUserResults(User $user, Collection $metrics, &$testCompiled, Request $request)
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

            if($request->test){
                $mergedUserTests = $mergedUserTests->filter(function($test) use($request) {
                    $testDisplayName = $test instanceof UserCustomTest ? $test->relatedCustomTest->display_name : $test->testType->display_name;
                    return $testDisplayName == $request->test;
                });
            }

            $mergedUserTests->each(function($userTest) use($metrics, $user, &$testCompiled) {
                $testDisplayName = $userTest instanceof UserCustomTest ?
                                    $userTest->relatedCustomTest->display_name :
                                    $userTest->testType->display_name;
            
                
                $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics, $user->latestOrganizationalClimateCollection['collection_type_name']);
                $questions = $userTest->answers->map(fn($answer) => $answer instanceof UserCustomAnswer ?
                                                                                $answer->relatedQuestion : 
                                                                                $answer->parentQuestion);
    
                $this->updateAnswers($user, $testDisplayName, $evaluatedTest, $questions->keyBy('id'), $testCompiled);
            });
        }
    }

    private function updateAnswers(User $user, string $testDisplayName, array $evaluatedTest, $questions, &$testCompiled)
    {
        foreach ($evaluatedTest['processed_answers'] as $questionNumber => $answer) {
            $testCompiled[$testDisplayName][$questions[$questionNumber]->statement]['Geral']['answers'][] = $answer;
            $testCompiled[$testDisplayName][$questions[$questionNumber]->statement][$user->department]['answers'][] = $answer;
        }
    }

    private function calculateAverageScoreByQuestion(&$testCompiled)
    {
        foreach ($testCompiled as $testName => $test) {
            foreach ($test as $questionStatement => $question) {
                foreach ($question as $departmentName => $departmentAnswers) {
                    $count = count($departmentAnswers['answers']);
                    $sum = array_sum($departmentAnswers['answers']);
                    $testCompiled[$testName][$questionStatement][$departmentName]['total_average'] = $sum / $count;
                    unset($testCompiled[$testName][$questionStatement][$departmentName]['answers']);
                }
            }
        }
    }
}

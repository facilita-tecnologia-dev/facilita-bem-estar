<?php

namespace App\Http\Controllers\Private\Dashboard\Organizational;

use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrganizationalAnswersController
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

        $this->pageData = $this->pageQuery($request->test);

        $organizationalClimateResults = $this->getCompiledPageData();

        $filtersApplied = array_filter($request->query(), fn($queryParam) => $queryParam != null);
        
        return view('private.dashboard.organizational.by-answers', compact(
            'organizationalClimateResults',
            'filtersApplied'
        ));
    }

    private function pageQuery($filteredTest = null)
    {
        $pageData = session('company')
            ->users()
            ->has('collections')
            ->select('users.id', 'users.name', 'users.department', 'users.gender', 'users.admission', 'users.birth_date')
            ->withLatestOrganizationalClimateCollection(only: $filteredTest)
            ->get();

        return $pageData;
    }

    private function getCompiledPageData()
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->pageData as $user) {
            foreach ($user->latestOrganizationalClimateCollection->tests as $userTest) {

                $testDisplayName = $userTest->testType->display_name;

                $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

                $questions = $userTest->testType->questions->keyBy('id');
  
                foreach ($evaluatedTest['processed_answers'] as $questionNumber => $answer) {
                    $testCompiled[$testDisplayName][$questions[$questionNumber]->statement]['Geral']['answers'][] = $answer;
                    $testCompiled[$testDisplayName][$questions[$questionNumber]->statement][$user->department]['answers'][] = $answer;
                }
            }
        }

        foreach ($testCompiled as $testName => $test) {
            foreach($test as $questionStatement => $question)
            foreach ($question as $departmentName => $departmentAnswers) {
                $count = count($departmentAnswers['answers']);
                $sum = array_sum($departmentAnswers['answers']);
                $testCompiled[$testName][$questionStatement][$departmentName]['total_average'] = $sum / $count;
                unset($testCompiled[$testName][$questionStatement][$departmentName]['answers']);
            }
        }

        krsort($testCompiled);
        
        return $testCompiled;
    }


}

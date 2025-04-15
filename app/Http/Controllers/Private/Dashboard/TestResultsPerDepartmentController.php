<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Models\Company;
use App\Services\TestService;
use Illuminate\Support\Facades\Gate;

class TestResultsPerDepartmentController
{
    protected $testService;

    protected $companyUserCollections;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function __invoke($testName)
    {
        Gate::authorize('view-manager-screens');

        $this->companyUserCollections = $this->pageQuery($testName);

        $resultsPerDepartment = $this->getCompiledTestsData();

        return view('private.dashboard.test-results-per-department', compact(
            'testName',
            'resultsPerDepartment',
        ));
    }

    private function pageQuery($testName)
    {
        $companyUserCollections = Company::where('id', session('company')->id)
            ->with('metrics.metricType')
            ->with('users', function ($user) use ($testName) {
                $user
                    ->has('collections')
                    ->with('latestCollections', function ($latestCollection) use ($testName) {
                        $latestCollection
                            ->whereHas('tests', function ($q) use ($testName) {
                                $q->whereHas('testType', function ($subQuery) use ($testName) {
                                    $subQuery->where('display_name', $testName);
                                });
                            })
                            ->with('collectionType')
                            ->with('tests', function ($userTest) use ($testName) {
                                $userTest
                                    ->whereHas('testType', function ($q) use ($testName) {
                                        $q->where('display_name', $testName);
                                    })
                                    ->with(['answers', 'questions.options', 'testType.risks.relatedQuestions']);
                            })->limit(1);
                    });
            })
            ->first();

        return $companyUserCollections;
    }

    /**
     * Compila os dados dividindo-os por setor para enviar para a view
     */
    private function getCompiledTestsData()
    {
        $testCompiled = [];

        foreach ($this->companyUserCollections->users as $user) {
            $userTest = $user->latestCollections[0]->tests[0];
            $userDepartment = $user->department;

            if (! isset($testCompiled[$userDepartment]['total'])) {
                $testCompiled[$userDepartment]['total'] = 0;
            }

            $testCompiled[$userDepartment]['total']++;

            $answers = [];

            foreach ($userTest->answers as $answer) {
                $question = $userTest->questions->where('id', $answer->question_id)->first();
                $relatedOption = $question->options->where('id', $answer->question_option_id)->first();
                $answers[$question->id] = $relatedOption->value;
            }

            $evaluatedTest = $this->testService->evaluateTest($userTest, $answers, $this->companyUserCollections->metrics);

            if (! isset($testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']])) {
                $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['count'] = 0;
                $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['severity_color'] = $evaluatedTest['severity_color'];
            }

            $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['count']++;
        }

        return $testCompiled;
    }
}

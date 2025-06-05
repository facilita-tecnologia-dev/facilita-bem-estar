<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Services\TestService;
use App\Services\User\UserFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PsychosocialResultsByDepartmentController
{
    protected $testService;

    protected $pageData;

    protected $filterService;

    public function __construct(TestService $testService, UserFilterService $filterService)
    {
        $this->testService = $testService;
        $this->filterService = $filterService;
    }

    public function __invoke(Request $request, $testName)
    {
        Gate::authorize('psychosocial-dashboard-view');
        $this->pageData = $this->query($request, $testName);

        $resultsPerDepartment = $this->getCompiledPageData();

        return view('private.dashboard.psychosocial.by-department', compact(
            'testName',
            'resultsPerDepartment',
        ));
    }

    private function query(Request $request, string $testName)
    {
        $query = session('company')->users()->getQuery();

        return $this->filterService->apply($query)
            ->whereHas('latestPsychosocialCollection')
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->withLatestPsychosocialCollection(function ($query) use ($request, $testName) {
                $query->whereYear('created_at', $request->year ?? '2025')
                    ->withCollectionTypeName('psychosocial-risks')
                    ->withTests(function ($query) use ($testName) {
                        $query
                            ->justOneTest($testName)
                            ->withAnswers()
                            ->withAnswersSum()
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

    /**
     * Compila os dados dividindo-os por setor para enviar para a view
     */
    private function getCompiledPageData()
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->pageData as $user) {
   
            $userTest = $user->latestPsychosocialCollection?->tests[0];
            $userDepartment = $user->department;

            $this->sumDepartmentCount($userDepartment, $testCompiled);

            $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

            $this->compileTestResults($userDepartment, $evaluatedTest, $testCompiled);

            $this->sortSeveritiesByDepartment($userDepartment, $testCompiled);
        }

        return $testCompiled;
    }

    private function sumDepartmentCount(string $userDepartment, array &$testCompiled)
    {
        if (! isset($testCompiled[$userDepartment]['total'])) {
            $testCompiled[$userDepartment]['total'] = 0;
        }

        $testCompiled[$userDepartment]['total']++;
    }

    private function compileTestResults(string $userDepartment, array $evaluatedTest, array &$testCompiled)
    {
        if (! isset($testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']])) {
            $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['count'] = 0;
            $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['severity_color'] = $evaluatedTest['severity_color'];
            $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['severity_key'] = $evaluatedTest['severity_key'];
        }

        $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['count']++;
    }

    private function sortSeveritiesByDepartment(string $userDepartment, array &$testCompiled)
    {
        uasort($testCompiled[$userDepartment]['severities'], function ($a, $b) {
            return $b['severity_key'] <=> $a['severity_key'];
        });
    }
}

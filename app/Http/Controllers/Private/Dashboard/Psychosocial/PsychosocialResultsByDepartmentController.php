<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Services\TestService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PsychosocialResultsByDepartmentController
{
    protected $testService;

    protected $pageData;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function __invoke(Request $request, $testName)
    {
        Gate::authorize('view-manager-screens');

        // Catching users
        $query = session('company')->users()
            ->whereHas('latestPsychosocialCollection', function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            })
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->getQuery();

        // Catching user tests
        $this->pageData = $query
            ->withLatestPsychosocialCollection(function ($query) use ($request, $testName) {
                $query->whereYear('created_at', $request->year ?? '2025')
                    ->withCollectionTypeName('psychosocial-risks')
                    ->withTests(function ($query) use ($testName) {
                        $query
                            ->justOneTest($testName)
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

        $resultsPerDepartment = $this->getCompiledPageData();

        return view('private.dashboard.psychosocial.by-department', compact(
            'testName',
            'resultsPerDepartment',
        ));
    }

    /**
     * Compila os dados dividindo-os por setor para enviar para a view
     */
    private function getCompiledPageData()
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->pageData as $user) {
            $userTest = $user->latestPsychosocialCollection->tests[0];
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

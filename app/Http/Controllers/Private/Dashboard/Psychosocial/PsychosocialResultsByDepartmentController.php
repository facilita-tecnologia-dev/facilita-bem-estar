<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Services\TestService;
use Illuminate\Support\Facades\Gate;

class PsychosocialResultsByDepartmentController
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

        return view('private.dashboard.psychosocial.test-results-per-department', compact(
            'testName',
            'resultsPerDepartment',
        ));
    }

    private function pageQuery($testName)
    {
        $companyUserCollections = session('company')
            ->users()
            ->has('collections')
            ->select('users.id', 'users.department', 'users.gender', 'users.admission', 'users.birth_date')
            ->withLatestPsychosocialCollection(only: $testName)
            ->get();

        return $companyUserCollections;
    }

    /**
     * Compila os dados dividindo-os por setor para enviar para a view
     */
    private function getCompiledTestsData()
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->companyUserCollections as $user) {
            $userTest = $user->latestPsychosocialCollection->tests[0];
            $userDepartment = $user->department;

            if (! isset($testCompiled[$userDepartment]['total'])) {
                $testCompiled[$userDepartment]['total'] = 0;
            }

            $testCompiled[$userDepartment]['total']++;

            $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

            if (! isset($testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']])) {
                $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['count'] = 0;
                $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['severity_color'] = $evaluatedTest['severity_color'];
                $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['severity_key'] = $evaluatedTest['severity_key'];
            }

            uasort($testCompiled[$userDepartment]['severities'], function ($a, $b) {
                return $b['severity_key'] <=> $a['severity_key'];
            });

            $testCompiled[$userDepartment]['severities'][$evaluatedTest['severity_title']]['count']++;
        }

        return $testCompiled;
    }
}

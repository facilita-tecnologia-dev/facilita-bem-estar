<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Gate;

class TestResultsPerDepartmentController
{
    protected $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }

    public function __invoke($testName)
    {
        Gate::authorize('view-manager-screens');

        $resultsPerDepartment = $this->getCompiledResults($testName);

        return view('private.dashboard.test-results-per-department', compact(
            'testName',
            'resultsPerDepartment',
        ));
    }

    /**
     * Compila os dados dividindo-os por setor para enviar para a view
     */
    private function getCompiledResults(string $testName): array
    {
        $test = $this->helper->getCompanyUsersCollections(justEssentials: true, testName: $testName);

        $testsCompiled = [];

        foreach ($test->users as $user) {
            if (! isset($testsCompiled[$user->department]['total'])) {
                $testsCompiled[$user->department]['total'] = 0;
            }

            foreach ($user->latestCollection->tests as $userTest) {
                $testSeverityTitle = $userTest->severity_title;
                $testSeverityColor = $userTest->severity_color;

                if (! isset($testsCompiled[$user->department]['severities'][$testSeverityTitle]['count'])) {
                    $testsCompiled[$user->department]['severities'][$testSeverityTitle]['count'] = 0;
                }

                if (! isset($testsCompiled[$user->department]['severities'][$testSeverityTitle]['severity_color'])) {
                    $testsCompiled[$user->department]['severities'][$testSeverityTitle]['severity_color'] = '';
                }

                $testsCompiled[$user->department]['total'] += 1;
                $testsCompiled[$user->department]['severities'][$testSeverityTitle]['count'] += 1;
                $testsCompiled[$user->department]['severities'][$testSeverityTitle]['severity_color'] = (int) $testSeverityColor;
            }
        }

        foreach ($testsCompiled as $department) {
            usort($department['severities'], function ($a, $b) {
                return $b['severity_color'] <=> $a['severity_color'];
            });
        }

        return $testsCompiled;
    }
}

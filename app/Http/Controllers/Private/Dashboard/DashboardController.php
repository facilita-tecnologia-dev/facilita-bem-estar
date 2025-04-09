<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Helpers\Helper;
use Illuminate\Support\Facades\Gate;

class DashboardController
{
    protected $companyUsersCollections;

    public function __construct(Helper $helper)
    {
        $this->companyUsersCollections = $helper->getCompanyUsersCollections(justEssentials: true, risks: true, metrics: true);
    }

    public function __invoke()
    {
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }

        $dashboardResults = $this->getCompiledResults();
        $testsParticipation = $this->getTestsParticipation();
        $metrics = $this->companyUsersCollections->metrics;

        return view('private.dashboard.index', [
            'dashboardResults' => $dashboardResults,
            'testsParticipation' => $testsParticipation,
            'metrics' => $metrics,
        ]);
    }

    /**
     * Retorna um array com os dados compilados de todos os testes.
     */
    private function getCompiledResults(): array
    {
        $testCompiled = [];

        foreach ($this->companyUsersCollections->users as $user) {
            foreach ($user->latestCollection->tests as $userTest) {
                $testDisplayName = $userTest->testType->display_name;
                $severityTitle = $userTest->severity_title;

                if (! isset($testCompiled[$testDisplayName]['severities'][$severityTitle])) {
                    $testCompiled[$testDisplayName]['severities'][$severityTitle] = [
                        'count' => 0,
                        'severity_color' => $userTest->severity_color,
                    ];
                }

                $testCompiled[$testDisplayName]['severities'][$severityTitle]['count'] += 1;
            }

            foreach ($user->latestCollection->risks as $risk) {
                $points = $risk->points;
                $parentTest = $risk->parentRisk->relatedTest->display_name;

                $testCompiled[$parentTest]['risks'][$risk->parentRisk->name]['score'][] = $points;
            }
        }

        foreach ($testCompiled as $testName => $item) {
            foreach ($item['risks'] as $riskName => $testRisk) {

                $average = array_sum($testRisk['score']) / count($testRisk['score']);
                $testCompiled[$testName]['risks'][$riskName]['score'] = ceil($average);

                if ($average > 2) {
                    $testCompiled[$testName]['risks'][$riskName]['risk'] = 'Risco Alto';
                } elseif ($average > 1) {
                    $testCompiled[$testName]['risks'][$riskName]['risk'] = 'Risco Médio';
                } else {
                    $testCompiled[$testName]['risks'][$riskName]['risk'] = 'Risco Baixo';
                }
            }
        }

        return $testCompiled;
    }

    /**
     * Retorna um array com a quantidade de usuários que realizaram/não realizaram os testes.
     *
     * @return array [Total de usuários, usuários que realizaram os testes]
     */
    private function getTestsParticipation(): array
    {
        $participatingUserCount = $this->companyUsersCollections->users->count();
        $nonParticipatingUserCount = session('company')->users->count() - $participatingUserCount;

        $testsParticipation = [$participatingUserCount, $nonParticipatingUserCount];

        return $testsParticipation;
    }
}

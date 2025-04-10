<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Helpers\Helper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;

class RisksController
{
    protected $companyRisks;

    public function __construct(Helper $helper)
    {
        $this->companyRisks = $helper->getCompanyUsersCollections(risks: true, tests: false);
    }

    public function __invoke()
    {
        Gate::authorize('view-manager-screens');

        $risks = $this->getRisks(true);

        return view('private.dashboard.risks', compact('risks'));
    }

    public function generatePDF()
    {
        Gate::authorize('view-manager-screens');
        
        $risks = $this->getRisks();
        $company = session('company');

        $companyLogo = $company->logo;
        $companyName = $company->name;

        $pdf = Pdf::loadView('pdf.risks-inventory', [
            'risks' => $risks,
            'companyLogo' => $companyLogo,
            'companyName' => $companyName,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('inventario_de_riscos.pdf');
    }

    private function getRisks($onlyCritical = false)
    {
        $testCompiled = [];

        foreach ($this->companyRisks->users as $user) {
            foreach ($user->latestCollection->risks as $risk) {
                $testDisplayName = $risk->parentRisk->relatedTest->display_name;
                $riskDisplayName = $risk->parentRisk->name;

                $testCompiled[$testDisplayName][$riskDisplayName]['score'][] = $risk->points;
                $testCompiled[$testDisplayName][$riskDisplayName]['control-actions'] = $risk->parentRisk->controlActions;
            }
        }

        foreach ($testCompiled as $testName => $test) {
            foreach ($test as $riskName => $risk) {
                $average = array_sum($risk['score']) / count($risk['score']);

                if ($onlyCritical) {
                    if (ceil($average) != 3) {
                        unset($testCompiled[$testName][$riskName]);
                    } else {
                        $testCompiled[$testName][$riskName]['score'] = ceil($average);
                        $testCompiled[$testName][$riskName]['risk'] = 'Risco Alto';
                    }
                } else {
                    $testCompiled[$testName][$riskName]['score'] = ceil($average);

                    if ($average > 2) {
                        $testCompiled[$testName][$riskName]['risk'] = 'Risco Alto';
                    } elseif ($average > 1) {
                        $testCompiled[$testName][$riskName]['risk'] = 'Risco Médio';
                    } else {
                        $testCompiled[$testName][$riskName]['risk'] = 'Risco Baixo';
                    }
                }
            }
        }

        return $testCompiled;
    }
}

<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Services\TestService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;

class PsychosocialRisksController
{
    protected $testService;

    protected $companyUserCollections;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
        $this->companyUserCollections = $this->pageQuery();
    }

    public function __invoke()
    {
        Gate::authorize('view-manager-screens');

        $risks = $this->getRisks(true);

        return view('private.dashboard.psychosocial.risks', compact('risks'));
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

    private function pageQuery()
    {
        $companyUserCollections = session('company')
            ->users()
            ->has('collections')
            ->select('users.id', 'users.department', 'users.gender', 'users.admission', 'users.birth_date')
            ->withLatestPsychosocialCollection()
            ->get();

        return $companyUserCollections;
    }

    private function getRisks($onlyCritical = false)
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];
        foreach ($this->companyUserCollections as $user) {
            foreach ($user->latestPsychosocialCollection->tests as $userTest) {
                $testDisplayName = $userTest->testType->display_name;
                $answers = [];

                foreach ($userTest->answers as $answer) {
                    $question = $userTest->testType->questions->where('id', $answer->question_id)->first();
                    $relatedOption = $question->options->where('id', $answer->question_option_id)->first();
                    $answers[$question->id] = $relatedOption->value;
                }

                $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

                if (isset($evaluatedTest['risks'])) {
                    foreach ($evaluatedTest['risks'] as $riskName => $risk) {
                        $testCompiled[$testDisplayName][$riskName]['score'][] = $risk['riskPoints'];
                        $testCompiled[$testDisplayName][$riskName]['controlActions'] = $risk['controlActions'];
                    }
                }
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

                    $testCompiled[$testName][$riskName]['control-actions'] = 'Risco Alto';
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

        // dd($testCompiled);
        return $testCompiled;
    }
}

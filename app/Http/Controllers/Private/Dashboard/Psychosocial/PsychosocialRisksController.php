<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Enums\RiskLevelEnum;
use App\Enums\RiskSeverityEnum;
use App\Models\User;
use App\Services\TestService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class PsychosocialRisksController
{
    protected $testService;

    protected $pageData;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;

        // Catching users
        $query = session('company')->users()
            ->whereHas('latestPsychosocialCollection', function ($query) {
                $query->whereYear('created_at', Carbon::now()->year);
            })
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->getQuery();

        // Catching user tests
        $this->pageData = $query
            ->withLatestPsychosocialCollection(function ($query) {
                $query->whereYear('created_at', '2025')
                    ->withCollectionTypeName('psychosocial-risks')
                    ->withTests(function ($query) {
                        $query
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

    public function __invoke()
    {
        Gate::authorize('psychosocial-dashboard-view');
        $risks = $this->getCompiledPageData(true);
        

        return view('private.dashboard.psychosocial.risks', compact('risks'));
    }

    public function generatePDF()
    {
        Gate::authorize('psychosocial-dashboard-view');
        $risks = $this->getCompiledPageData();

        $company = session('company');

        $companyLogo = $company->logo;
        $companyName = $company->name;

        $pdf = Pdf::loadView('pdf.test', [
            'risks' => $risks,
            'companyLogo' => $companyLogo,
            'companyName' => $companyName,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream($companyName . ' - Inventário de Riscos Psicossociais.pdf');
    }

    private function getCompiledPageData($onlyCritical = false)
    {
        $metrics = session('company')->metrics;

        $testCompiled = [];

        foreach ($this->pageData as $user) {
            if ($user->latestPsychosocialCollection) {
                $this->compileUserTests($user, $metrics, $testCompiled);
            }
        }

        $this->updateRisksAverage($onlyCritical, $testCompiled);


        return $testCompiled;
    }

    private function compileUserTests(User $user, Collection $metrics, array &$testCompiled)
    {
        foreach ($user['latestPsychosocialCollection']['tests'] as $userTest) {
            $testDisplayName = $userTest->testType->display_name;

            $evaluatedTest = $this->testService->evaluateTest($userTest, $metrics);

            if (isset($evaluatedTest['risks'])) {
                $this->updateRisks($testDisplayName, $evaluatedTest, $testCompiled);
            }
        }
    }

    private function updateRisks(string $testDisplayName, array $evaluatedTest, array &$testCompiled)
    {
        foreach ($evaluatedTest['risks'] as $riskName => $risk) {
            $testCompiled[$testDisplayName][$riskName]['score'][] = $risk['riskLevel'];
            $testCompiled[$testDisplayName][$riskName]['severity'] = $risk['severity'];
            $testCompiled[$testDisplayName][$riskName]['probability'] = $risk['probability'];
            $testCompiled[$testDisplayName][$riskName]['controlActions'] = $risk['controlActions'];
        }
    }

    private function updateRisksAverage(bool $onlyCritical, array &$testCompiled)
    {
        foreach ($testCompiled as $testName => $test) {
            foreach ($test as $riskName => $risk) {
                $average = round(array_sum($risk['score']) / count($risk['score']));

                $this->calculateRiskAverage($average, $onlyCritical, $testName, $riskName, $testCompiled);
            }
        }
    }

    private function calculateRiskAverage(float $average, bool $onlyCritical, string $testName, string $riskName, array &$testCompiled)
    {
        if ($onlyCritical) {
            if ($average < 3) {
                unset($testCompiled[$testName][$riskName]);
            } else {
                $testCompiled[$testName][$riskName]['score'] = $average;
                $testCompiled[$testName][$riskName]['risk'] = RiskLevelEnum::labelFromValue($average);
            }
        } else {
            $testCompiled[$testName][$riskName]['score'] = $average;
            $testCompiled[$testName][$riskName]['risk'] = RiskLevelEnum::labelFromValue($average);
        }
    }
}

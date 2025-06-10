<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Enums\RiskLevelEnum;
use App\Enums\RiskSeverityEnum;
use App\Models\Test;
use App\Models\User;
use App\Services\TestService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PsychosocialRisksController
{
    protected $testService;

    protected $pageData;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function __invoke(Request $request)
    {
        Gate::authorize('psychosocial-dashboard-view');

        $this->pageData = $this->query($request);

        $riskResults = $this->getCompiledPageData(true);

        return view('private.dashboard.psychosocial.risks', compact('riskResults'));
    }

    public function generatePDF(Request $request)
    {
        Gate::authorize('psychosocial-dashboard-view');

        $this->pageData = $this->query($request);

        $risks = $this->getCompiledPageData();

        $company = session('company');
        $companyLogo = $company->logo;
        $companyName = $company->name;

        $pdf = Pdf::loadView('pdf.risks-inventory', [
            'risks' => $risks,
            'companyLogo' => $companyLogo,
            'companyName' => $companyName,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($companyName . ' - Inventário de Riscos Psicossociais.pdf');
    }

    private function query(Request $request)
    {
        return Test::where('collection_id', 1)
        ->withUserTests(function($query) use($request) {
            $query
            ->whereYear('created_at', $request->year ?? Carbon::now()->year)
            ->whereHas('parentCollection', function ($query) {
                $query
                ->where('company_id', session('company')->id)
                ->whereHas('userOwner');
            })
            ->withAvg(['answers as average_value'], 'value')
            ->with(['parentCollection.userOwner']);
        })
        ->withRisks(function($query) use($request) {
            $query->withRelatedQuestions(function($query) use($request) {
                $query
                ->withParentQuestionStatement()
                ->withParentQuestionInverted()
                ->withAnswerAverage($request);
            });
        })
        ->get();
    }

    private function getCompiledPageData($onlyCritical = false)
    {
        $testCompiled = [];
        
        foreach($this->pageData as $testType){
            $this->compileTests($testType, $testCompiled, $onlyCritical);
        }

        foreach($testCompiled as &$test){
            foreach($test['risks'] as $key => $risk){
                if($onlyCritical && $risk['riskLevel'] < 3){
                    unset($test['risks'][$key]);
                }
            }
        }

        return $testCompiled;
    }

    private function compileTests($testType, &$testCompiled, $onlyCritical)
    {
        $testType->average = round($testType['userTests']->avg('average_value'), 2);
        
        foreach($testType['userTests'] as $userTest){
            $evaluatedTest = $this->testService->evaluateTest($testType, $userTest, session('company')->metrics);
        }
        
        foreach($evaluatedTest['risks'] as &$risk){
            $risk['riskCaption'] = RiskLevelEnum::labelFromValue($risk['riskLevel']);
        }

        $testCompiled[$testType['display_name']] = $evaluatedTest;
    }
}

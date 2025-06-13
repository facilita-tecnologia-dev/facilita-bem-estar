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
        $psychosocialCampaign = session('company')->campaigns()
            ->whereYear('end_date', now()->year)
            ->whereHas('collection', function($query){
                $query->where('collection_id', 1);
            })
        ->first();

        if($psychosocialCampaign){
            $psychosocialCollection = $psychosocialCampaign['collection'];

            $customTests = $psychosocialCollection
                ->tests()
                ->with('userTests', function($query){
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
            ->get();

            $testTypes = Test::where('collection_id', 1)
                ->withRisks(function($query) {
                    $query->withRelatedQuestions(function($query) {
                        $query
                        ->withParentQuestionStatement()
                        ->withParentQuestionInverted()
                        ->withAnswerAverage();
                    });
                })
            ->get();
            
            foreach($testTypes as $test){
                $relatedTest = $customTests->firstWhere('key_name', $test['key_name']);
                $test->userTests = $relatedTest['userTests'];
            }
        }

        return $testTypes ?? [];
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

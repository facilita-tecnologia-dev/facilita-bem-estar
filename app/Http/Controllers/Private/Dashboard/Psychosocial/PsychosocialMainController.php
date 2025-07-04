<?php

namespace App\Http\Controllers\Private\Dashboard\Psychosocial;

use App\Enums\RiskLevelEnum;
use App\Models\Test;
use App\Services\Dashboard\PsychosocialRiskService;
use App\Services\TestService;
use App\Services\User\UserFilterService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PsychosocialMainController
{
    protected $testService;

    protected $filterService;

    protected $psychosocialService;

    protected $pageData;

    public function __construct(PsychosocialRiskService $psychosocialService, TestService $testService, UserFilterService $filterService)
    {
        $this->psychosocialService = $psychosocialService;
        $this->testService = $testService;
        $this->filterService = $filterService;
    }

    public function __invoke(Request $request)
    {
        Gate::authorize('psychosocial-dashboard-view');

        $this->pageData = $this->query($request);

        $psychosocialRiskResults = $this->getCompiledPageData();
        $userTests = isset($this->pageData[0]['userTests']) ? $this->pageData[0]['userTests'] : collect();

        $psychosocialTestsParticipation = $this->psychosocialService->getParticipation($userTests);
        
        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);

        return view('private.dashboard.psychosocial.index', [
            'psychosocialRiskResults' => $psychosocialRiskResults,
            'psychosocialTestsParticipation' => $psychosocialTestsParticipation,
            'companyHasTests' => session('company')->users()->has('collections')->exists(),
            'filtersApplied' => $filtersApplied,
            'filteredUserCount' => count($userTests) > 0 ? count($userTests) : null,
        ]);
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
            ->with('userTests', function($query) use($request) {
                $query
                ->whereYear('created_at', $request->year ?? Carbon::now()->year)
                ->whereHas('parentCollection', function ($query) {
                    $query
                    ->where('company_id', session('company')->id)
                    ->whereHas('userOwner', function ($query) {
                        $this->filterService->apply($query);
                    });
                })
                ->withAvg(['answers as average_value'], 'value')
                ->with(['parentCollection.userOwner']);
            })
            ->get();

            $testTypes = Test::where('collection_id', 1)
            ->withRisks(function($query) use($request) {
                $query->withRelatedQuestions(function($query) use($request) {
                    $query
                    ->withParentQuestionStatement()
                    ->withParentQuestionInverted()
                    ->withAnswerAverage($request);
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

    private function getCompiledPageData()
    {
        $testCompiled = [];

        foreach($this->pageData as $testType){
            if($testType['userTests']->count()){
                $this->compileTests($testType, $testCompiled);
            }
        }

        return $testCompiled;
    }

    private function compileTests($testType, &$testCompiled)
    {
        $testType->average = round($testType['userTests']->avg('average_value'), 2);
        
        foreach($testType['userTests'] as $userTest){
            $evaluatedTest = $this->testService->evaluateTest($testType, $userTest, session('company')->metrics);
            $this->updateTestSeverities($testType, $evaluatedTest, $testCompiled);
        }
        
        foreach($evaluatedTest['risks'] as &$risk){
            $risk['riskCaption'] = RiskLevelEnum::labelFromValue($risk['riskLevel']);
        }

        $testCompiled[$testType['display_name']] = array_merge($testCompiled[$testType['display_name']], $evaluatedTest);
    }

    private function updateTestSeverities(Test $testType, array &$evaluatedTest, array &$testCompiled)
    {
        if (! isset($testCompiled[$testType['display_name']]['severities'][$evaluatedTest['severity_title']])) {
            $testCompiled[$testType['display_name']]['severities'][$evaluatedTest['severity_title']] = [
                'count' => 0,
                'severity_color' => $evaluatedTest['severity_color'],
            ];
        }

        $testCompiled[$testType['display_name']]['severities'][$evaluatedTest['severity_title']]['count'] += 1;
    }


}

<?php

namespace App\Http\Controllers;

use App\Enums\RiskLevelEnum;
use App\Enums\RiskSeverityEnum;
use App\Models\ActionPlan;
use App\Models\Risk;
use App\Models\Test;
use App\Services\TestService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class ActionPlanController
{
    protected $testService;

    protected $pageData;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
        $this->pageData = $this->query();
    }

    public function show(ActionPlan $actionPlan)
    {
        Gate::authorize('action-plan-edit');

        $riskResults = $this->getCompiledPageData();
        
        return view('private.action-plan.show', compact('actionPlan', 'riskResults'));
    }

    public function edit(ActionPlan $actionPlan, string $riskName)
    {
        Gate::authorize('action-plan-edit');

        $risk = Risk::firstWhere('name', $riskName);

        $severities = collect(RiskSeverityEnum::cases())
            ->map(fn($case) => [
                'option' => $case->label(),
                'value'  => $case->value,
            ])
        ->all();

        $riskResults = $this->getCompiledPageData();

        $identifiedRisk = $this->findByRiskKey($riskResults, $riskName);

        return view('private.action-plan.edit', compact('actionPlan', 'riskName', 'risk', 'severities', 'identifiedRisk'));
    }

    private function query()
    {
        return Test::where('collection_id', 1)
        ->withUserTests(function($query) {
            $query
            ->whereYear('created_at', Carbon::now()->year)
            ->whereHas('parentCollection', function ($query) {
                $query
                ->where('company_id', session('company')->id)
                ->whereHas('userOwner');
            })
            ->withAvg(['answers as average_value'], 'value')
            ->with(['parentCollection.userOwner']);
        })
        ->withRisks(function($query) {
            $query->withRelatedQuestions(function($query) {
                $query
                ->withParentQuestionStatement()
                ->withParentQuestionInverted()
                ->withAnswerAverage();
            });
        })
        ->get();
    }

    private function getCompiledPageData()
    {
        $testCompiled = [];
        
        foreach($this->pageData as $testType){
            $this->compileTests($testType, $testCompiled);
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

    private function findByRiskKey(array $array, string $riskName): ?array
    {
        foreach ($array as $area) {
            if (isset($area['risks']) && array_key_exists($riskName, $area['risks'])) {
                return $area['risks'][$riskName];
            }
        }
        return null;
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\RiskLevelEnum;
use App\Enums\RiskSeverityEnum;
use App\Models\ActionPlan;
use App\Models\CustomControlAction;
use App\Models\Risk;
use App\Models\Test;
use App\Services\TestService;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function update(Request $request, ActionPlan $actionPlan, Risk $risk)
    {
        $validatedData = $request->validate([
            'risk.*.control_actions' => ['required', 'array'],
            'risk.*.control_actions.*.content' => ['required', 'string'],
            'risk.*.control_actions.*.deadline' => ['nullable', 'string'],
            'risk.*.control_actions.*.assignee' => ['nullable', 'string'],
            'risk.*.control_actions.*.status' => ['nullable', 'string'],
        ]);

        foreach($validatedData['risk'][$risk->id]['control_actions'] as $controlActionId => $controlAction){
            $controlActionRelated = CustomControlAction::firstWhere('id', $controlActionId);
        
            $controlActionRelated['content'] = $controlAction['content'];
            $controlActionRelated['deadline'] = $controlAction['deadline'];
            $controlActionRelated['assignee'] = $controlAction['assignee'];
            $controlActionRelated['status'] = $controlAction['status'];

            $controlActionRelated->save();
        }

        session(['company' => session('company')->load('actionPlan.controlActions')]);

        return back()->with('message', 'Medidas de controle atualizadas com sucesso!');
    }

    private function query()
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
                ->select('id', 'custom_collection_id', 'display_name', 'key_name', 'handler_type')
                ->with('userTests', function($query) {
                    $query
                    ->select('id', 'user_collection_id', 'test_id')
                    ->whereYear('created_at', Carbon::now()->year)
                    ->whereHas('parentCollection', function ($query) {
                        $query
                        ->where('company_id', session('company')->id);
                    })
                    ->withAvg(['answers as average_value'], 'value')
                    ->with([
                        'parentCollection' => function ($query) {
                            $query->select('id', 'user_id')->with(['userOwner:id,department' ]);
                        }
                    ]);
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
            ->select('id', 'collection_id', 'key_name', 'display_name', 'handler_type')
            ->get();

            foreach($testTypes as $test){
                $relatedTest = $customTests->firstWhere('key_name', $test['key_name']);
                $test->userTests = $relatedTest['userTests'];
            }
        }
        
        foreach($testTypes as $test){
            $relatedTest = $customTests->firstWhere('key_name', $test['key_name']);
            $test->userTests = $relatedTest['userTests'];
        }

        return $testTypes;
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
        $testCompiled[$testType['display_name']] = [];

        $testType->average = round($testType['userTests']->avg('average_value'), 2);
        
        $evaluatedTest = $this->testService->evaluateTests($testType, session('company')->metrics);
        
        foreach($evaluatedTest['risks'] as &$risk){
            $risk['riskCaption'] = RiskLevelEnum::labelFromValue($risk['riskLevel']);
        }

        $testCompiled[$testType['display_name']] = array_merge($testCompiled[$testType['display_name']], $evaluatedTest);
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

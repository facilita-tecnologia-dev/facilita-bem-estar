<?php

namespace App\Http\Controllers;

use App\Enums\RiskSeverityEnum;
use App\Models\ActionPlan;
use App\Models\ControlAction;
use App\Models\CustomControlAction;
use App\Models\Risk;
use Illuminate\Http\Request;

class CustomControlActionController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, ActionPlan $actionPlan, Risk $risk)
    {
        $validatedData = $request->validate([
            'control_action' => ['required'],
            'severity' => ['required'],
            'deadline' => ['nullable'],
            'assignee' => ['nullable'],
            'status' => ['nullable'],
        ]);

        CustomControlAction::create([
            'company_id' => session('company')->id,
            'action_plan_id' => $actionPlan->id,
            'risk_id' => $risk->id,
            'content' => $validatedData['control_action'],
            'severity' => $validatedData['severity'],
            'deadline' => $validatedData['deadline'] ?? null,
            'assignee' => $validatedData['assignee'] ?? null,
            'status' => $validatedData['status'] ?? null,
        ]);

        session(['company' => session('company')->load('actionPlan.controlActions')]);

        return back()->with('message', 'Medida de Controle criada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActionPlan $actionPlan, Risk $risk, CustomControlAction $controlAction)
    {
        $severities = collect(RiskSeverityEnum::cases())
            ->map(fn($case) => [
                'option' => $case->label(),
                'value'  => $case->value,
            ])
        ->all();

        return view('private.control-action.edit', compact('actionPlan', 'risk', 'controlAction', 'severities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActionPlan $actionPlan, Risk $risk, CustomControlAction $controlAction)
    {
        $validatedData = $request->validate([
            'control_action' => ['required'],
            'severity' => ['required'],
            'deadline' => ['nullable'],
            'assignee' => ['nullable'],
            'status' => ['nullable'],
        ]);

        $controlAction->content = $validatedData['control_action'];
        $controlAction->severity = $validatedData['severity'];
        $controlAction->deadline = $validatedData['deadline'];
        $controlAction->assignee = $validatedData['assignee'];
        $controlAction->status = $validatedData['status'];

        $controlAction->save();

        session(['company' => session('company')->load('actionPlan.controlActions')]);

        return to_route('action-plan.risk.edit', ['actionPlan' => $actionPlan, 'risk' => $risk->name])->with('message', 'Medida de controle atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActionPlan $actionPlan, Risk $risk, CustomControlAction $controlAction)
    {
        $controlAction->delete();

        session(['company' => session('company')->load('actionPlan.controlActions')]);

        return back()->with('message', 'Medida de Controle excluída com sucesso!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ControlAction;
use App\Models\CustomControlAction;
use App\Models\Risk;
use Illuminate\Http\Request;

class CustomControlActionController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $risks = Risk::with(['controlActions', 'customControlActions'])->get();
        
        $mappedControlActions = $risks->mapWithKeys(function($risk){
            $overrides = $risk->customControlActions
            ->filter(fn($customControlAction) => $customControlAction['control_action_id'])
            ->keyBy('control_action_id');

            $final = $risk->controlActions->map(function ($defaultControlAction) use ($overrides) {
                if ($overrides->has($defaultControlAction['id'])) {
                    $customControlAction = $overrides[$defaultControlAction['id']];

                    return $customControlAction;
                }
                
                return $defaultControlAction;
            });

            $customNew = $risk->customControlActions
            ->filter(fn($customControlAction) => !$customControlAction['control_action_id']);

            $finalControlActions = $final->concat($customNew);

            $finalControlActions = $finalControlActions->sortBy(
                fn($item) => $item->content
            );

            return [$risk->name => $finalControlActions];
        });

        $risksToSelect = $risks->map(fn($risk) => $risk->name);

        return view('private.control-actions.index', compact('mappedControlActions', 'risksToSelect'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'control_action' => ['required'],
            'risk' => ['required'],
        ]);

        CustomControlAction::create([
            'company_id' => session('company')->id,
            'risk_id' => Risk::firstWhere('name', $validatedData['risk'])->id,
            'content' => $validatedData['control_action'],
            'allowed' => true,
        ]);

        return back()->with('message', 'Medida de Controle criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomControlAction $customControlAction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomControlAction $customControlAction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'control_action_id' => ['required'],
            'control_action_content' => ['required'],
        ]);

        $explodedId = explode('_', $validatedData['control_action_id']);
        $riskId = $explodedId[1];

        
        if(str_starts_with($validatedData['control_action_id'], 'default')){
            $controlAction = ControlAction::firstWhere('id', $riskId);
            CustomControlAction::create([
                'company_id' => session('company')->id,
                'risk_id' => $controlAction->parentRisk->id,
                'control_action_id' => $controlAction->id,
                'content' => $validatedData['control_action_content'],
                'allowed' => false,
            ]);
        } else{
            $customControlAction = CustomControlAction::firstWhere('id', $riskId);
  
            $customControlAction->allowed = !($customControlAction->allowed);

            $customControlAction->save();
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomControlAction $controlAction)
    {
        $controlAction->delete();

        return back()->with('message', 'Medida de Controle excluída com sucesso!');
    }
}

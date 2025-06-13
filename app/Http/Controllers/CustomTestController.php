<?php

namespace App\Http\Controllers;

use App\Models\CustomCollection;
use App\Models\CustomTest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CustomTestController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CustomCollection $customCollection)
    {
        $validatedData = $request->validate([
            "custom_collection_id" => ['required', 'integer'],
            "display_name" => ['required', 'string'],
            "statement" => ['nullable', 'string'],
            "order" => ['required', 'integer'],
        ]);
     
        CustomTest::create([
            'custom_collection_id' => $customCollection->id,
            'key_name' => Str::slug($validatedData['display_name']),
            'display_name' => $validatedData['display_name'],
            'statement' => $validatedData['statement'],
            'order' => $validatedData['order'],
        ]);

        session(['company' => session('company')->load('customCollections.tests')]);

        return to_route('custom-collections.show', $customCollection);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomCollection $customCollection, CustomTest $customTest)
    {
        $customTestOrder = $customTest->order;
        
        $customTest->delete();
        
        $customCollection->tests->where('order', '>', $customTestOrder)->each(function($test){
            $test->order = $test->order - 1;
            $test->save();
        }); 
        
        return back()->with('message', 'Teste excluído com sucesso!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CustomCollection;
use App\Models\CustomQuestion;
use App\Models\CustomQuestionOption;
use App\Models\CustomTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomQuestionController
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CustomCollection $customCollection)
    {
        $validatedData = $request->validate([
            "custom_test_id" => ['required', 'integer'],
            "statement" => ['required', 'string']
        ]);
        
        DB::transaction(function() use($validatedData) {
            $options = session('company')->customCollections->firstWhere('collection_id', 2)->tests[0]->questions[0]->options;
   
            $customQuestion = CustomQuestion::create([
                'custom_test_id' => $validatedData['custom_test_id'],
                'statement' => $validatedData['statement']
            ]);

            foreach($options as $option){
                CustomQuestionOption::create([
                    'custom_question_id' => $customQuestion->id,
                    'content' => $option['content'],
                    'value' => $option['value'],
                ]);
            }
        });

        session(['company' => session('company')->load('customCollections.tests.questions')]);

        return back()->with('message', 'Questão adicionada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomCollection $customCollection, CustomTest $customTest, CustomQuestion $customQuestion)
    {
        $customQuestion->delete();

        return back()->with('message', 'Questão excluída com sucesso!');
    }
}

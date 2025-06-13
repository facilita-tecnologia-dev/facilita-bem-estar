<?php

namespace App\Http\Controllers;

use App\Models\CustomCollection;
use App\Models\CustomQuestion;
use App\Models\CustomQuestionOption;
use App\Models\CustomTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomCollectionController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customCollections = session('company')->customCollections()
        ->with('tests.questions')
        ->withCount('tests')
        ->get();

        return view('private.custom-collections.index', compact('customCollections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('private.custom-collections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            "name" => ['required', 'string', 'min:4'],
            "collection_type" => ['required', 'string'],
            "description" => ['nullable', 'string']
        ]);

        $customCollection = DB::transaction(function() use($validatedData) {
            $defaultCollection = session('company')->customCollections->where('collection_id', 2)->where('is_default')->first();

            $customCollection = CustomCollection::create([
                'company_id' => session('company')->id,
                'collection_id' => 2,
                'is_default' => false,
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
            ]);

            foreach($defaultCollection['tests'] as $test){
                $customTest = CustomTest::create([
                    'custom_collection_id' => $customCollection['id'],
                    'key_name' => $test['key_name'],
                    'display_name' => $test['display_name'],
                    'statement' => $test['statement'],
                    'order' => $test['order'],
                    'handler_type' => $test['handler_type'],
                    'reference' => $test['reference'],
                    'number_of_questions' => $test['number_of_questions'],
                ]);

                foreach($test['questions'] as $question){
                    $customQuestion = CustomQuestion::create([
                        'custom_test_id' => $customTest['id'],
                        'statement' => $question['statement']
                    ]);

                    foreach($question['options'] as $option){
                        CustomQuestionOption::create([
                            'custom_question_id' => $customQuestion['id'],
                            'content' => $option['content'],
                            'value' => $option['value']
                        ]);
                    }
                }
            }

            return $customCollection;
        });

        
        session(['company' => session('company')->load('customCollections')]);

        return to_route('custom-collections.show', $customCollection);
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomCollection $customCollection)
    {
        $customCollection->loadCount('tests');

        return view('private.custom-collections.show', compact('customCollection'));
    }

    public function update(Request $request, CustomCollection $customCollection)
    {
        $validatedData = $request->validate([
            'collection_name' => ['required', 'string'],
            'collection_description' => ['nullable', 'string'],
            'tests.*.display_name' => ['required', 'string'],
            'tests.*.statement' => ['nullable', 'string'],
            'tests.*.questions' => ['array', 'min:1'],
        ]);

        
        DB::transaction(function() use($customCollection, $validatedData) {
            // Updating collection data
            $customCollection['name'] = $validatedData['collection_name'];
            $customCollection['description'] = $validatedData['collection_description'];
            $customCollection->save();
            
            // Updating collection tests
            foreach($validatedData['tests'] as $testId => $test){
                $customTest = CustomTest::firstWhere('id', $testId);

                $customTest['display_name'] = $test['display_name'];
                $customTest['statement'] = $test['statement'];
                $customTest->save();

                foreach($test['questions'] as $questionId => $questionStatement){
                    $customQuestion = CustomQuestion::firstWhere('id', $questionId);
                    
                    $customQuestion['statement'] = $questionStatement;
                    $customQuestion->save();
                }

            }
        });

        return back()->with('message', 'Pesquisa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomCollection $customCollection)
    {
        $customCollection->delete();

        return back()->with('message', "Coleção de Testes excluída com sucesso!");
    }
}

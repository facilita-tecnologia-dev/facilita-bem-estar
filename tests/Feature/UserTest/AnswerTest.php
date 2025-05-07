<?php

use App\Models\Collection;
use App\Models\Company;
use App\Models\Test;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::first());
    session(['company' => Company::first()]);
});

it('test form should be rendered', function(){
    $collection = Collection::where('key_name', 'psychosocial-risks')->first();

    $response = $this->get(route('responder-teste', $collection));

    $response->assertOk();
    $response->assertViewHas('test', function (Test $test) use($collection) {
        return $test->parentCollection->id === $collection->id;
    });
    $response->assertViewHasAll(['testIndex', 'pendingAnswers', 'collection']);
});

it('psychosocial tests should be answerable', function(){
    $collection = Collection::where('key_name', 'psychosocial-risks')->first();
    $tests = $collection->tests;
    
    foreach($tests as $test){
        $questions = $test->questions;
    
        $answers = $questions->mapWithKeys(function($question){
            return [
                $question->id => (string) rand(1,5),
            ];
        })->toArray();

        $nextTest = $collection->tests()->where('order', $test['order'] + 1)->first();

        $response = $this->post(route('enviar-teste', ['collection' => $collection, 'test' => $test['order']]), $answers);


        if($nextTest == null){
            $response->assertRedirectToRoute('responder-teste.thanks');
            $resultsOnSession = array_filter(array_keys(session()->all()), fn($item) => str_ends_with($item, '|result'));
            expect($resultsOnSession)->toBeEmpty();
        } else{
            $response->assertSessionHas("$collection->key_name|$test->key_name|result");
            $response->assertRedirectToRoute('responder-teste', ['collection' => $collection, 'test' => $nextTest['order']]);
        }
    }
});

it('organizational tests should be answerable', function(){
    $collection = Collection::where('key_name', 'organizational-climate')->first();
    $tests = $collection->tests;

    foreach($tests as $test){
        $questions = $test->questions;
        
        $answers = $questions->mapWithKeys(function($question){
            return [
                $question->id => (string) rand(1,5),
            ];
        })->toArray();
        
        $nextTest = $collection->tests()->where('order', $test['order'] + 1)->first();

        $response = $this->post(route('enviar-teste', ['collection' => $collection, 'test' => $test['order']]), $answers);
        

        if($nextTest == null){
            $response->assertRedirectToRoute('feedbacks.create');
            $resultsOnSession = array_filter(array_keys(session()->all()), fn($item) => str_ends_with($item, '|result'));
            expect($resultsOnSession)->toBeEmpty();
        } else{
            $response->assertSessionHas("$collection->key_name|$test->key_name|result");
            $response->assertRedirectToRoute('responder-teste', ['collection' => $collection, 'test' => $nextTest['order']]);
        }
    }
});
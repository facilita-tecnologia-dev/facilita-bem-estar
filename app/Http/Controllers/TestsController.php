<?php

namespace App\Http\Controllers;

use App\Services\TestService;
use Illuminate\Http\Request;

class TestsController
{

    protected $testService;

    public function __construct(TestService $testService)
    {
        $this->testService = $testService;   
    }

    public function index(string $test){
        if(! $this->testService->testExists($test)){
            return back();
        }

        return view('tests.' . $test);
    }

    public function handleTestSubmitted(Request $request, $test){
        $testInfo = $this->testService->getTestInfo($test);

        if(!$testInfo){
            return back();
        }

        // Cria as regras de validação dinamicamente
        $validationRules = [];
        for ($i = 1; $i <= $testInfo['numberOfQuestions']; $i++) {
            $validationRules['question_' . $i] = 'required';
        }

        // Valida todas as respostas
        $validatedData = $request->validate($validationRules);

        $result = $this->testService->processTest($test, $validatedData);

        if(!empty($testInfo['nextTest'])){
            return to_route('test', $testInfo['nextTest']);
        }

        return back();
    }


    // public function results()
    // {
    //     $results = $this->testService->getAllResults();
    //     return view('tests.results', compact('results'));
    // }

  
}

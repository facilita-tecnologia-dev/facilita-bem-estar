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

        $testInfo = $this->testService->getTestInfo($test) ?? '';

        return view('tests.' . $test, ['testInfo' => $testInfo]);
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

        if($test === 'estresse'){
            return to_route('results.dashboard');
        }

        if(!empty($testInfo['nextStep'])){
            return to_route('test', $testInfo['nextStep']);
        }

        return back();
    }
}

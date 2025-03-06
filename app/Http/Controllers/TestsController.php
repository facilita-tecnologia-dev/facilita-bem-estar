<?php

namespace App\Http\Controllers;

use App\Models\TestCollection;
use App\Models\TestForm;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $testResults = collect(session()->all())
            ->filter(function ($value, $key) {
                return str_ends_with($key, '_result');
            })
            ->toArray();


            $newTestCollection = TestCollection::create([
                'user_id' => Auth::user()->id,
            ]);


            foreach($testResults as $testResult){
                TestForm::create([
                    'test_collection_id' => $newTestCollection->id,
                    'testName' => $testResult['testName'],
                    'total_points' => $testResult['totalPoints'],
                    'severityTitle' => $testResult['severityTitle'],
                    'severityColor' => $testResult['severityColor'],
                    'recommendation' => $testResult['recommendations'][0]
                ]);
            }
            
            return to_route('test-results');
        }

        if(!empty($testInfo['nextStep'])){
            return to_route('test', $testInfo['nextStep']);
        }

        return back();
    }
}

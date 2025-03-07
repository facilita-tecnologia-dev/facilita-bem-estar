<?php

namespace App\Http\Controllers;

use App\Models\TestCollection;
use App\Models\TestForm;
use App\Models\TestQuestion;
use App\Models\TestType;
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
        $testTypeInfo = TestType::query()->where('key_name', '=', $test)->first();
        $testQuestions = TestQuestion::query()->where('test_type_id', '=', $testTypeInfo->id)->with('questionOptions')->get();

        return view('tests.' . $test, ['testQuestions' => $testQuestions]);
    }

    public function handleTestSubmitted(Request $request, $test){
        $testInfo = TestType::query()->where('key_name', '=', $test)->first();

        if(!$testInfo){
            return back();
        }

        $validatedData = $this->validateAnswers($request, $testInfo);

        $result = $this->testService->processTest($test, $validatedData, $testInfo);
        

        if($test === 'estresse'){
            $allTestResults = $this->getAllResultsFromSession();
            
            $storedResults = $this->storeResultsOnDatabase($allTestResults);

            if(!$storedResults){
                return back();
            }

            return to_route('test-results');
        }

        if(!empty($testInfo['next_step'])){
            return to_route('test', $testInfo['next_step']);
        }

        return back();
    }

    private function validateAnswers($request, $testInfo){
        $validationRules = [];
        for ($i = 1; $i <= $testInfo['number_of_questions']; $i++) {
            $validationRules['question_' . $i] = 'required';
        }

        $validatedData = $request->validate($validationRules);
        return $validatedData;
    }

    private function getAllResultsFromSession(){
        $allTestResults = collect(session()->all())
        ->filter(function ($value, $key) {
            return str_ends_with($key, '_result');
        })
        ->toArray();

        return $allTestResults;
    }

    private function storeResultsOnDatabase($allTestResults){
        $newTestCollection = TestCollection::create([
            'user_id' => Auth::user()->id,
        ]);
  
        foreach($allTestResults as $testResult){
            $testType = TestType::query()->where('display_name', '=', $testResult['testName'])->first();

            TestForm::create([
                'test_collection_id' => $newTestCollection->id,
                'testName' => $testResult['testName'],
                'test_type_id' => $testType->id,
                'total_points' => $testResult['totalPoints'],
                'severityTitle' => $testResult['severityTitle'],
                'severityColor' => $testResult['severityColor'],
                'recommendation' => $testResult['recommendations'][0]
            ]);
        }

        return $allTestResults;
    }
}

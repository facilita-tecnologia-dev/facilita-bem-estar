<?php

namespace App\Http\Controllers\Private;

use App\Models\PendingTestAnswer;
use App\Models\QuestionOption;
use App\Models\TestAnswer;
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

    public function __invoke($testIndex = 1){
        $test = TestType::query()
        ->where('order', '=', $testIndex)
        ->with('questions', function($query){
            $query->inRandomOrder()->with('questionOptions', function($q){
                $q->orderBy('value');
            });    
        }
        )->firstOrFail();
        
        $pendingAnswers = PendingTestAnswer::query()->where('user_id', '=', Auth::user()->id)->where('test_type_id', '=', $test->id)->get();

        return view('test', [
            'test' => $test,
            'testIndex' => $testIndex,
            'pendingAnswers' => $pendingAnswers ?? [],
        ]);
    }

    public function handleTestSubmit(Request $request, $testIndex){
        $testInfo = TestType::query()
        ->where('order', '=', $testIndex)
        ->with('questions.questionOptions')
        ->first();
        
        $validationRules = $this->generateValidationRules($testInfo);
        $validatedData = $request->validate($validationRules);
        
        $processedTest = $this->testService->processTest($validatedData, $testInfo);

        $totalTests = TestType::max('order');
        if ($testIndex == $totalTests) {
            $testResults = $this->getTestResultsFromSession();
            $storedResults = $this->storeResultsOnDatabase($testResults);
            
            if(!$storedResults){
                return back();
            }

            $request->session()->forget(array_keys($testResults));

            return to_route('choose-test');
        }

        return to_route('test', $testIndex + 1);
    }

    private function generateValidationRules($testInfo): array {
        $validationRules = [];

        $testQuestions = $testInfo->questions->groupBy('id');

        foreach($testQuestions as $question){
            $validationRules[$question[0]->id] = 'required';
        }
        
        return $validationRules;
    }

    private function getTestResultsFromSession(): array {
        $allTestResults = collect(session()->all())
        ->filter(function ($_, $key) {
            return str_ends_with($key, '-result');
        })
        ->toArray();

        return $allTestResults;
    }

    private function storeResultsOnDatabase($testResults): array {
        PendingTestAnswer::query()->where('user_id', '=', Auth::user()->id)->delete();

        $newTestCollection = TestCollection::create([
            'user_id' => Auth::user()->id,
        ]);
        foreach($testResults as $key => $testResult){
            $testKeyName = str_replace("-result", "", $key);
            $testType = TestType::query()->where('key_name', '=', $testKeyName)->first();
            
            $testForm = TestForm::create([
                'test_collection_id' => $newTestCollection->id,
                'test_name' => $testType->display_name,
                'test_type_id' => $testType->id,
                'total_points' => $testResult['total_points'],
                'severity_title' => $testResult['severity_title'],
                'severity_color' => $testResult['severity_color'],
                'recommendation' => '',
            ]);

            foreach($testResult['answers'] as $questionId => $answer){
                TestAnswer::create([
                    'test_question_id' => $questionId,
                    'test_form_id' => $testForm->id,
                    'value' => $answer
                ]);
            }
        }

        return $testResults;
    }
}

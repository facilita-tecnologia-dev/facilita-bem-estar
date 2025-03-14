<?php

namespace App\Http\Controllers\Users;

use App\Models\PendingTestAnswer;
use App\Models\QuestionOption;
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

    public function index(Request $request, string $index){
        if($index == 0){
            return to_route('welcome');
        }

        // Busca informações do teste no banco de dados
        $testTypeInfo = TestType::query()->where('order', '=', $index)->first();
        
        $testIndex = $testTypeInfo['order'];
        $testName = $testTypeInfo['display_name'];
        $testStatement = $testTypeInfo['statement'];
        $testReference = $testTypeInfo['reference'];

        // Busca as questões do teste
        $testQuestions = TestQuestion::query()->where('test_type_id', '=', $testTypeInfo->id)->with('questionOptions')->get();
        
        $pendingAnswers = PendingTestAnswer::query()->where('user_id', '=', Auth::user()->id)->where('test_type_id', '=', $testTypeInfo->id)->get();


        return view('user.test', [
            'testIndex' => $testIndex,
            'testName' => $testName,
            'testStatement' => $testStatement,
            'testQuestions' => $testQuestions,
            'testReference' => $testReference,
            'pendingAnswers' => $pendingAnswers ?? [],
        ]);
    }

    public function handleTestSubmit(Request $request, $testIndex){

        // Busca as informações para verificar se o teste existe
        $testInfo = TestType::query()->where('order', '=', $testIndex)->first();
        
        if(!$testInfo){
            return back();
        }

        // Gera as regras de validação dinamicamente
        $validationRules = $this->generateValidationRules($testInfo);
        
        // Valida as respostas
        $validatedData = $request->validate($validationRules);

        // Processa o teste
        $result = $this->testService->processTest($validatedData, $testInfo);
        
        $testInfos = TestType::query()
        ->where('id', '=', $testIndex)
        ->with('questions', function($query){
            return $query->with('questionOptions');
        })->first();

        PendingTestAnswer::query()->where('test_type_id', '=', $testInfos->id)->delete();
        
        foreach(array_values($result['answers']) as $questionNumber => $answer){
            $question = $testInfos->questions[$questionNumber];
            $selectedOption = $question->questionOptions->firstWhere('value', $answer);

            PendingTestAnswer::query()->create([
                'value' => $answer,
                'user_id' => Auth::user()->id,
                'test_type_id' => $testInfos->id,
                'test_question_id' => $question->id,
                'question_option_id' => $selectedOption->id
            ]);
        }

        // Se for o último teste
        if($testIndex === "11"){
            $allTestResults = $this->getAllResultsFromSession();
            
            $storedResults = $this->storeResultsOnDatabase($allTestResults);
            
            if(!$storedResults){
                return back();
            }

            $keysToRemoveFromSession = array_keys($allTestResults);

            $request->session()->forget($keysToRemoveFromSession);

            return to_route('test-results');
        }

        // Envia para o próximo passo
        if(!empty($testInfo['order'])){
            return to_route('test', $testIndex + 1);
        }

        return back();
    }

    private function generateValidationRules($testInfo): array {
        $validationRules = [];
        for ($i = 1; $i <= $testInfo['number_of_questions']; $i++) {
            $validationRules['question_' . $i] = 'required';
        }

        return $validationRules;
    }

    private function getAllResultsFromSession(): array {
        $allTestResults = collect(session()->all())
        ->filter(function ($_, $key) {
            return str_ends_with($key, '_result');
        })
        ->toArray();

        return $allTestResults;
    }

    private function storeResultsOnDatabase($allTestResults): array {
        PendingTestAnswer::query()->where('user_id', '=', Auth::user()->id)->delete();

        $newTestCollection = TestCollection::create([
            'user_id' => Auth::user()->id,
        ]);
  
        foreach($allTestResults as $testResult){
            $testType = TestType::query()->where('display_name', '=', $testResult['test_name'])->first();

            TestForm::create([
                'test_collection_id' => $newTestCollection->id,
                'test_name' => $testResult['test_name'],
                'test_type_id' => $testType->id,
                'total_points' => $testResult['total_points'],
                'severity_title' => $testResult['severity_title'],
                'severity_color' => $testResult['severity_color'],
                'recommendation' => $testResult['recommendations'][0]
            ]);
        }

        return $allTestResults;
    }
}

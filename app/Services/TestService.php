<?php 

namespace App\Services;

use App\Handlers\TestHandlerFactory;
use App\Models\PendingTestAnswer;
use App\Models\TestType;
use App\Repositories\TestRepository;
use Illuminate\Support\Facades\Auth;

class TestService
{
    protected $handlerFactory;

    public function __construct(TestHandlerFactory $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
    }

    public function processTest(array $data, TestType $testInfo){
        $answersValues = array_map(function($value){
            return (int) $value;
        }, $data);

        $handler = $this->handlerFactory->getHandler($testInfo);
        $processedTest = $handler->process($answersValues);

        session([$testInfo->key_name . '-result' => $processedTest]);

        $pendingAnswers = [];
        $questions = $testInfo->questions->pluck('questionOptions', 'id')->all();

        foreach($processedTest['answers'] as $questionId => $answer){
            $questionOptions = $questions[$questionId];
            $option = collect($questionOptions)->firstWhere('value', $answer);

            if($option){
                $pendingAnswers[] = [
                    'value' => $answer,
                    'question_option_id' => $option->id,
                    'test_question_id' => $questionId,
                    'test_type_id' => $testInfo->id,
                    'user_id' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        if (!empty($pendingAnswers)) {
            PendingTestAnswer::where('test_type_id', '=', $pendingAnswers[0]['test_type_id'])->delete();

            PendingTestAnswer::insert($pendingAnswers);
        }
        
        return $processedTest;
    }
}
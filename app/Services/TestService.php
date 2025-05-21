<?php

namespace App\Services;

use App\Handlers\TestHandlerFactory;
use App\Helpers\AuthGuardHelper;
use App\Models\Collection;
use App\Models\CustomTest;
use App\Models\PendingTestAnswer;
use App\Models\Test;
use App\Models\UserCustomTest;
use App\Models\UserTest;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class TestService
{
    protected TestHandlerFactory $handlerFactory;

    public function __construct(TestHandlerFactory $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
    }

    public function process(Collection $collection, Test | CustomTest $test, array $answers): bool
    {
        $answersValues = array_map(function ($value) {
            return (int) $value;
        }, $answers);
    
        session(["$collection->key_name|$test->key_name|result" => $answersValues]);
        
        // $pendingAnswers = [];
        // $questions = $test->questions->keyBy('id');


        // foreach ($answersValues as $questionId => $answer) {
        //     $question = $questions[$questionId];
        //     $option = $question['options']->firstWhere('value', $answer);

        //     if ($option) {
        //         $pendingAnswers[] = [
        //             'value' => $answer,
        //             'question_option_id' => $option->id,
        //             'question_id' => $questionId,
        //             'test_id' => $test->id,
        //             'user_id' => AuthGuardHelper::user()->id,
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ];
        //     }
        // }

        // if (! empty($pendingAnswers)) {
        //     PendingTestAnswer::where('test_id', '=', $pendingAnswers[0]['test_id'])->delete();
        //     PendingTestAnswer::insert($pendingAnswers);
        // }

        return true;
    }

    public function evaluateTest(UserTest | UserCustomTest $userTest, EloquentCollection $metrics, ?string $collectionKeyName = null): array
    {        
        $testType = $userTest instanceof UserCustomTest ? $userTest->relatedCustomTest : $userTest->testType;

        $handler = $this->handlerFactory->getHandler($testType, $collectionKeyName ?? null);
        $evaluatedTest = $handler->process($userTest, $metrics);

        return $evaluatedTest;
    }
}

<?php

namespace App\Services;

use App\Handlers\TestHandlerFactory;
use App\Models\PendingTestAnswer;
use App\Models\Test;
use App\Models\UserTest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TestService
{
    protected TestHandlerFactory $handlerFactory;

    public function __construct(TestHandlerFactory $handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
    }

    public function process(array $answers, Test $test) : bool
    {
        $answersValues = array_map(function ($value) {
            return (int) $value;
        }, $answers);

        $parentCollection = $test->parentCollection['key_name'];
        session(["$parentCollection|$test->key_name|result" => $answersValues]);

        $pendingAnswers = [];
        $questions = $test->questions()->get()->keyBy('id');

        foreach ($answersValues as $questionId => $answer) {
            $question = $questions[$questionId];
            $option = $question['options']->firstWhere('value', $answer);

            if ($option) {
                $pendingAnswers[] = [
                    'value' => $answer,
                    'question_option_id' => $option->id,
                    'question_id' => $questionId,
                    'test_id' => $test->id,
                    'user_id' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($pendingAnswers)) {
            PendingTestAnswer::where('test_id', '=', $pendingAnswers[0]['test_id'])->delete();
            PendingTestAnswer::insert($pendingAnswers);
        }

        return true;
    }

    public function evaluateTest(UserTest $userTest, Collection $metrics) : array
    {
        $handler = $this->handlerFactory->getHandler($userTest->testType);
        $evaluatedTest = $handler->process($userTest, $metrics);

        return $evaluatedTest;
    }
}

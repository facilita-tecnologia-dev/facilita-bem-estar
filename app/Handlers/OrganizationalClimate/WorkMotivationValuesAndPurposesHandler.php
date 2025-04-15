<?php

namespace App\Handlers\OrganizationalClimate;

use App\Helpers\Helper;
use App\Models\Test;

class WorkMotivationValuesAndPurposesHandler
{
    public function process(Test $test, array $answers, $questions, $metrics, $risks): array
    {
        $processedAnswers = [];

        foreach ($questions as $question) {
            $answer = $answers[$question->id];
            $processedAnswers[$question->id] = Helper::multiplyAnswer($answer);
        }

        return [
            'processed_answers' => $processedAnswers,
        ];
    }
}

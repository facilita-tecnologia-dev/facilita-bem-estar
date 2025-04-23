<?php

namespace App\Handlers\OrganizationalClimate;

use App\Helpers\Helper;
use App\Models\UserTest;
use Illuminate\Support\Collection;

class WorkConditionsHandler
{
    public function process(UserTest $userTest, Collection $metrics): array
    {
        $processedAnswers = [];

        foreach ($userTest->answers as $answer) {
            $question = $userTest->testType->questions->where('id', $answer->question_id)->first();
            $processedAnswers[$question->id] = Helper::multiplyAnswer($answer->related_option_value);
        }

        return [
            'processed_answers' => $processedAnswers,
        ];
    }
}

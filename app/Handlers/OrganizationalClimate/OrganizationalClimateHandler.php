<?php

namespace App\Handlers\OrganizationalClimate;

use App\Helpers\Helper;
use App\Models\UserCustomAnswer;
use App\Models\UserCustomTest;
use App\Models\UserTest;
use Illuminate\Support\Collection;

class OrganizationalClimateHandler
{
    public function process(UserTest | UserCustomTest $userTest, Collection $metrics): array
    {
        $processedAnswers = [];
        
        foreach ($userTest->answers as $answer) {
            $questionId = $answer instanceof UserCustomAnswer ? $answer->custom_question_id : $answer->question_id;
            $processedAnswers[$questionId] = Helper::multiplyAnswer($answer['related_option_value']);
        }

        return [
            'processed_answers' => $processedAnswers,
        ];
    }
}

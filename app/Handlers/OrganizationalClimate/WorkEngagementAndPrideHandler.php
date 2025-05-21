<?php

namespace App\Handlers\OrganizationalClimate;

use App\Helpers\Helper;
use App\Models\UserTest;
use Illuminate\Support\Collection;
use App\Models\UserCustomTest;

class WorkEngagementAndPrideHandler
{
    public function process(UserTest | UserCustomTest $userTest, Collection $metrics): array
    {
        $processedAnswers = [];

        foreach ($userTest->answers as $answer) {
            $processedAnswers[$answer['question_id']] = Helper::multiplyAnswer($answer['related_option_value']);
        }

        return [
            'processed_answers' => $processedAnswers,
        ];
    }
}

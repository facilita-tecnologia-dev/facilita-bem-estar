<?php

namespace App\Handlers\OrganizationalClimate;

use App\Helpers\Helper;
use App\Models\CustomTest;
use App\Models\Test;
use App\Models\UserCustomAnswer;
use App\Models\UserCustomTest;
use App\Models\UserTest;
use Illuminate\Support\Collection;

class OrganizationalClimateHandler
{
    public function processIndividualTest(Test | CustomTest $testType, UserTest $userTest, Collection $metrics): array
    {
      $processedAnswers = [];
        foreach ($userTest->answers as $answer) {
            $questionId = $answer->question_id;
            $processedAnswers[$questionId] = Helper::multiplyAnswer($answer['value']);
        }

        return [
            'processed_answers' => $processedAnswers,
        ];
    }
}

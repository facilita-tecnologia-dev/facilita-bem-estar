<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class Irritabilidade implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, array $answers, $average, Collection $metrics, Collection $questions)
    {
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->question_Id];

            if ($answer >= 3) {
                $riskPoints++;
            }
        }

        return $riskPoints;
    }
}

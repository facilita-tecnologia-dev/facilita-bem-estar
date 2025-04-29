<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class DificuldadeConcentracao implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, $average, Collection $metrics)
    {
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion->related_question_answer;

            if ($answer >= 3) {
                $riskPoints++;
            }
        }

        return $riskPoints;
    }
}

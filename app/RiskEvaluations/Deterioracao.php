<?php

namespace App\RiskEvaluations;

class Deterioracao implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics, $questions): array
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

        return [
            'riskPoints' => $riskPoints,
        ];
    }
}

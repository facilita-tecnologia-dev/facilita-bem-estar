<?php

namespace App\RiskEvaluations;

class FaltaReconhecimento implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics, $questions): array
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->question_Id];
            if ($answer <= 2) {
                $riskPoints++;
            }
        }

        return [
            'riskPoints' => $riskPoints,
        ];
    }
}

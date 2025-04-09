<?php

namespace App\RiskEvaluations;

class DificuldadeConcentracao implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average): array
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->parentQuestion->id];

            if ($answer >= 3) {
                $riskPoints++;
            }
        }

        if ($riskPoints > 2) {
            $evaluatedRisk = 'Risco Alto';
        } elseif ($riskPoints > 1) {
            $evaluatedRisk = 'Risco Médio';
        } else {
            $evaluatedRisk = 'Risco Baixo';
        }

        return [
            'evaluatedRisk' => $evaluatedRisk,
            'riskPoints' => $riskPoints,
        ];
    }
}

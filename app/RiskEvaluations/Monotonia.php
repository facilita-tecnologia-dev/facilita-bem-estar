<?php

namespace App\RiskEvaluations;

class Monotonia implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average): array
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 2.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            if ($risk->parentQuestion->statement == 'As tarefas que executo em meu trabalho são variadas') {
                $answer = $answers[$risk->parentQuestion->id];
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }
        }

        if ($riskPoints > 1) {
            $evaluatedRisk = 'Risco Alto';
        } elseif ($riskPoints > 0) {
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

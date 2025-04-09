<?php

namespace App\RiskEvaluations;

class InjusticaPercebida implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average): array
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            if ($risk->parentQuestion->statement == 'Os gestores desta organização se consideram insubstituíveis') {
                $answer = $answers[$risk->parentQuestion->id];

                if ($answer >= 4) {
                    $riskPoints++;
                }
            }

            if ($risk->parentQuestion->statement == 'Existem oportunidades semelhante de ascensão para todas as pessoas') {
                $answer = $answers[$risk->parentQuestion->id];

                if ($answer <= 2) {
                    $riskPoints++;
                }
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

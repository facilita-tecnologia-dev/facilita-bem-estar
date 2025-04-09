<?php

namespace App\RiskEvaluations;

class PressaoExcessiva implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average): array
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            if ($risk->parentQuestion->statement == 'Os gestores desta organização fazem qualquer coisa para chamar a atenção') {
                $answer = $answers[$risk->parentQuestion->id];

                if ($answer >= 4) {
                    $riskPoints++;
                }
            }

            if ($risk->parentQuestion->statement == 'Há forte controle do trabalho') {
                $answer = $answers[$risk->parentQuestion->id];

                if ($answer >= 4) {
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

<?php

namespace App\RiskEvaluations;

class FaltaRecursos implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average): array
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            if ($risk->parentQuestion->statement == 'Os recursos de trabalho são em número suficiente para a realização das tarefas') {
                $answer = $answers[$risk->parentQuestion->id];

                if ($answer <= 2) {
                    $riskPoints++;
                }
            }

            if ($risk->parentQuestion->statement == 'Os equipamentos são adequados para a realização das tarefas') {
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

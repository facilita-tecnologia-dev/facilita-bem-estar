<?php

namespace App\RiskEvaluations;

class FaltaRecursos implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average): string
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->questionMaps as $risk) {
            if ($risk->question->statement == 'Os recursos de trabalho são em número suficiente para a realização das tarefas') {
                $answer = $answers[$risk->question->id];

                if ($answer <= 2) {
                    $riskPoints++;
                }
            }

            if ($risk->question->statement == 'Os equipamentos são adequados para a realização das tarefas') {
                $answer = $answers[$risk->question->id];

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

        return $evaluatedRisk;
    }
}

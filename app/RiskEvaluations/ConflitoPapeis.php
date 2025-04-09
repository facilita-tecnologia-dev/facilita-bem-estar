<?php

namespace App\RiskEvaluations;

class ConflitoPapeis implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average): string
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->questionMaps as $risk) {
            if ($risk->question->statement == 'Há clareza na definição das tarefas') {
                $answer = $answers[$risk->question->id];

                if ($answer <= 2) {
                    $riskPoints++;
                }
            }

            if ($risk->question->statement == 'As orientações que me são passadas para realizar as tarefas são coerentes entre si') {
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

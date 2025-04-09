<?php

namespace App\RiskEvaluations;

class RigidezOrganizacional implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average): string
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }

        foreach ($risk->questionMaps as $risk) {
            if ($risk->question->statement == 'Tenho autonomia para realizar as tarefas como julgo melhor') {
                $answer = $answers[$risk->question->id];

                if ($answer <= 2) {
                    $riskPoints++;
                }
            }

            if ($risk->question->statement == 'Tenho liberdade para opinar sobre o meu trabalho') {
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

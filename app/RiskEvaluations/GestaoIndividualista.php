<?php

namespace App\RiskEvaluations;

class GestaoIndividualista implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics): array
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            if ($risk->parentQuestion->statement == 'Aqui os gestores preferem trabalhar individualmente') {
                $answer = $answers[$risk->parentQuestion->id];

                if ($answer >= 4) {
                    $riskPoints++;
                }
            }

            if ($risk->parentQuestion->statement == 'O trabalho coletivo é valorizado pelos gestores') {
                $answer = $answers[$risk->parentQuestion->id];

                if ($answer <= 2) {
                    $riskPoints++;
                }
            }
        }

        $extraHours = $metrics->whereHas('metricType', function($query) {
            $query->where('key_name', 'extra-hours');
        })->first();

        if($extraHours && $extraHours->value > 50){
            if ($riskPoints <= 2) {
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

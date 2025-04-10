<?php

namespace App\RiskEvaluations;

class Monotonia implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics): array
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average <= 2.5) {
            $riskPoints += 1.5;
        }

        foreach ($risk->relatedQuestions as $risk) {
            if ($risk->parentQuestion->statement == 'As tarefas que executo em meu trabalho são variadas') {
                $answer = $answers[$risk->parentQuestion->id];
                if ($answer <= 2) {
                    $riskPoints += 1.5;
                }
            }
        }

        $turnover = $metrics->whereHas('metricType', function($query) {
            $query->where('key_name', 'turnover');
        })->first();

        if($turnover && $turnover->value < 20){
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

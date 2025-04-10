<?php

namespace App\RiskEvaluations;

class Afastamentos implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics): array
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

        $absences = $metrics->whereHas('metricType', function($query) {
            $query->where('key_name', 'absences');
        })->first();

        if($absences && $absences->value > 75){
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

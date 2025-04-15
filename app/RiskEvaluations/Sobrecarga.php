<?php

namespace App\RiskEvaluations;

class Sobrecarga implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics, $questions): array
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average < 3.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->question_Id];

            if ($answer <= 2) {
                $riskPoints++;
            }
        }

        $extraHours = $metrics->filter(function ($companyMetric) {
            return $companyMetric->metricType && $companyMetric->metricType->key_name === 'extra-hours';
        })->first();

        if ($extraHours && $extraHours->value > 50) {
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

            'riskPoints' => $riskPoints,
        ];
    }
}

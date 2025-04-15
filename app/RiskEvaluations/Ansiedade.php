<?php

namespace App\RiskEvaluations;

class Ansiedade implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics, $questions): array
    {
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->question_Id];

            if ($answer >= 4) {
                $riskPoints++;
            }
        }

        $turnover = $metrics->filter(function ($companyMetric) {
            return $companyMetric->metricType && $companyMetric->metricType->key_name === 'turnover';
        })->first();

        if ($turnover && $turnover->value > 50) {
            if ($riskPoints <= 2) {
                $riskPoints++;
            }
        }

        return [

            'riskPoints' => $riskPoints,
        ];
    }
}

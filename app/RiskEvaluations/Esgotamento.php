<?php

namespace App\RiskEvaluations;

class Esgotamento implements RiskEvaluatorInterface
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

        $absenteeism = $metrics->filter(function ($companyMetric) {
            return $companyMetric->metricType && $companyMetric->metricType->key_name === 'absenteeism';
        })->first();

        if ($absenteeism && $absenteeism->value > 75) {
            if ($riskPoints <= 2) {
                $riskPoints++;
            }
        }

        return [
            'riskPoints' => $riskPoints,
        ];
    }
}

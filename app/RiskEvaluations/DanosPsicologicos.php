<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class DanosPsicologicos implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, array $answers, $average, Collection $metrics, Collection $questions)
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

        if ($absenteeism && $absenteeism->value > 50) {
            if ($riskPoints <= 2) {
                $riskPoints++;
            }
        }

        return $riskPoints;
    }
}

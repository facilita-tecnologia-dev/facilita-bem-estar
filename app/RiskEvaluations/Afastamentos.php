<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class Afastamentos implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, array $answers, $average, Collection $metrics, Collection $questions)
    {
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->question_Id];

            if ($answer >= 3) {
                $riskPoints++;
            }
        }

        $absences = $metrics->filter(function ($companyMetric) {
            return $companyMetric->metricType && $companyMetric->metricType->key_name === 'absences';
        })->first();

        if ($absences && $absences->value > 75) {
            if ($riskPoints <= 2) {
                $riskPoints++;
            }
        }

        return $riskPoints;
    }
}

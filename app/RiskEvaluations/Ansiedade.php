<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class Ansiedade implements RiskEvaluatorInterface
{
    /**
     * @param Collection<int, \App\Models\Metric> $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics) : float | int
    {
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];

            if ($answer >= 4) {
                $riskPoints++;
            }
        }

        $turnover = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'turnover';
        })->first();

        if ($turnover && $turnover['value'] > 50) {
            if ($riskPoints <= 2) {
                $riskPoints++;
            }
        }

        return $riskPoints;
    }
}

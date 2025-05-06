<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class FaltaSuporte implements RiskEvaluatorInterface
{
    /**
     * @param Collection<int, \App\Models\Metric> $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics) : float | int
    {
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];

            if ($answer <= 2) {
                $riskPoints++;
            }
        }

        return $riskPoints;
    }
}

<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class Irritabilidade implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, int $testSeverity): float|int
    {
        $riskLevel = 1;

        if (!$average >= 3) {
            return $riskLevel;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];

            if (!$answer >= 3) {
                return $riskLevel;
            }
        }

        $probability = RiskService::calculateProbability($average);

        if($testSeverity < 2){
            return $riskLevel;
        }

        $riskLevel = match (true) {
            $probability == 1 && $testSeverity == 2 => 1,
            $probability == 2 && $testSeverity == 2 => 2,
            default => 1,
        };

        return $riskLevel;
    }
}

<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class Deterioracao implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, int $testSeverity): float|int
    {
        $riskLevel = 1;

        // dump($average, !$average >= 3);
        if (!$average >= 3) {
            return $riskLevel;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];
            if (!$answer >= 3) {
                return $riskLevel;
            }
        }
        
        $extraHours = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'extra-hours';
        })->first();

        if($extraHours->value !== 'null'){
            if($extraHours->value > 50){
                $probability = 3;
            } else {
                $probability = 2;
            }
        } else{        
            $probability = RiskService::calculateProbability($average);
        }

        if($testSeverity < 3){
            return $riskLevel;
        }
        
        $riskLevel = match (true) {
            ($probability == 3 && $testSeverity  == 3) || ($probability == 2 && $testSeverity == 3) => 3,
            default => 1,
        };

        return $riskLevel;
    }
}

<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class Sobrecarga implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, int $testSeverity): float|int
    {
        $riskLevel = 1;

        if (!$average > 3.5) {
            return $riskLevel;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];

            if (!$answer <= 2) {
                return $riskLevel;
            }
        }
        
        $extraHours = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'extra-hours';
        })->first();

        if($extraHours->value !== 'null'){
            if($extraHours->value > 75){
                $probability = 4;
            } else {
                $probability = 3;
            }
        } else{        
            $probability = RiskService::calculateProbability($average);
        }

        if($testSeverity < 3){
            return $riskLevel;
        }
                
        $riskLevel = match (true) {
            $probability == 2 && $testSeverity == 2 => 3,
            $probability == 3 && $testSeverity == 2 => 4,
            default => 1,
        };


        return $riskLevel;
    }
}

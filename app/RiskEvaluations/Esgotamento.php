<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class Esgotamento implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, int $testSeverity): float|int
    {
        $riskLevel = 1;

        if (!$average >= 3.5) {
            return $riskLevel;
        }


        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];
            if (!$answer >= 4) {
                return $riskLevel;
            }
        }

        $absenteeism = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'absenteeism';
        })->first();

        if($absenteeism->value !== 'null'){
            if($absenteeism->value > 75){
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
            $probability == 3 && $testSeverity == 3 => 3,
            $probability == 4 && $testSeverity == 3 => 4,
            default => 1,
        };

        return $riskLevel;
    }
}

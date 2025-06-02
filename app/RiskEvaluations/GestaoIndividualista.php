<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class GestaoIndividualista implements RiskEvaluatorInterface
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
            $parentQuestionStatement = $riskQuestion['parent_question_statement'];

            if ($parentQuestionStatement == 'Aqui os gestores preferem trabalhar individualmente') {
                if (!$answer >= 4) {
                    return $riskLevel;
                }
            }

            if ($parentQuestionStatement == 'O trabalho coletivo é valorizado pelos gestores') {
                if (!$answer <= 2) {
                    return $riskLevel;
                }
            }
        }

        $turnover = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'turnover';
        })->first();

        if($turnover->value !== 'null'){
            if($turnover->value > 50){
                $probability = 3;
            } else {
                $probability = 2;
            }
        } else{        
            $probability = RiskService::calculateProbability($average);
        }

        if($testSeverity < 2){
            return $riskLevel;
        }
        
        $riskLevel = match (true) {
            $probability == 2 && $testSeverity == 2 => 2,
            $probability == 3 && $testSeverity == 2 => 3,
            default => 1,
        };

        return $riskLevel;
    }
}

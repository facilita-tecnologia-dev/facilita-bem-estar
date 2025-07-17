<?php

namespace App\RiskEvaluations;

use App\Enums\RiskLevelEnum;
use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class DanosPsicologicos implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, ?UserTest $userTest = null): array
    {
        $riskSeverity = 4;

        // Probabilidade
        $absenteeism = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'absenteeism';
        })->first();

        if($absenteeism->value > 75){
            $probability = 4;
        } else {
            $probability = 3;
        }

        $riskLevel = 1;

        if (!($average >= 3.5)) {
            $allAnswersBelowCondition = true;

            foreach ($risk->relatedQuestions as $riskQuestion) {
                $averageAnswers = $userTest ? $userTest->answers->firstWhere('question_id', $riskQuestion['question_Id'])->value : $riskQuestion->average_value;
                
                if (!($averageAnswers >= 4)) {
                    $allAnswersBelowCondition = false;
                    break;
                }
            }
            
            if ($allAnswersBelowCondition) {
                $riskSeverity--;
            }
        }
        
        $riskLevel = RiskService::calculateRiskLevel($probability, $riskSeverity);
        
        return compact('probability', 'riskLevel', 'riskSeverity');
    }
}

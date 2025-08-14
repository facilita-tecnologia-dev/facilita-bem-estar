<?php

namespace App\RiskEvaluations;

use App\Enums\RiskLevelEnum;
use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class PressaoExcessiva implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, ?UserTest $userTest = null): array
    {
        $riskSeverity = 3;

        $turnover = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'turnover';
        })->first();

        if($turnover && $turnover->value > 50){
            $probability = 3;
        } else {
            $probability = 2;
        }

        $riskLevel = 1;
        $allAnswersBelowCondition = false;

        if (!($average >= 3.5)) {
            $allAnswersBelowCondition = true;

            foreach ($risk->relatedQuestions as $riskQuestion) {
                if(session('company')->id === 1){
                    $averageAnswers =  $riskQuestion->average_value;
                } else{
                    $averageAnswers = $userTest ? $userTest->answers->firstWhere('question_id', $riskQuestion['question_Id'])->value : $riskQuestion->average_value;
                }
                
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

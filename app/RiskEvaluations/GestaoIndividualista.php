<?php

namespace App\RiskEvaluations;

use App\Enums\RiskLevelEnum;
use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class GestaoIndividualista implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, ?UserTest $userTest = null): array
    {
        $riskSeverity = 2;

        $turnover = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'turnover';
        })->first();

        if($turnover && $turnover->value > 50){
            $probability = 3;
        } else {
            $probability = 2;
        }

        $riskLevel = 1;

        if (!($average >= 3)) {
            $allAnswersBelowCondition = true;

            foreach ($risk->relatedQuestions as $riskQuestion) {
                $averageAnswers = $userTest ? $userTest->answers->firstWhere('question_id', $riskQuestion['question_Id'])->value : $riskQuestion->average_value;
                $parentQuestionStatement = $riskQuestion['parent_question_statement'];
    
                if ($riskQuestion['parent_question_statement'] == 'Aqui os gestores preferem trabalhar individualmente') {
                    if (!($averageAnswers >= 4)) {
                        $allAnswersBelowCondition = false;
                        break;
                    }
                }
    
                if ($riskQuestion['parent_question_statement'] == 'O trabalho coletivo é valorizado pelos gestores') {
                    if (!($averageAnswers <= 2)) {
                        $allAnswersBelowCondition = false;
                        break;
                    }
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

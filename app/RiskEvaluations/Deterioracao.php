<?php

namespace App\RiskEvaluations;

use App\Enums\RiskLevelEnum;
use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class Deterioracao implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, ?UserTest $userTest = null): array
    {
        $riskSeverity = 3;

        // Probabilidade
        $extraHours = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'extra-hours';
        })->first();

        if($extraHours && $extraHours->value > 50){
            $probability = 3;
        } else {
            $probability = 2;
        }

        $riskLevel = 1;

        if (!($average >= 3)) {
            $allAnswersBelowCondition = true;

            foreach ($risk->relatedQuestions as $riskQuestion) {
                // dd($riskQuestion);
                // $averageAnswers = $userTest ? $userTest->answers->firstWhere('question_id', $riskQuestion['question_Id'])->value : $riskQuestion->average_value;
                $averageAnswers = rand(1,5);
                if (!($averageAnswers >= 3)) {
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

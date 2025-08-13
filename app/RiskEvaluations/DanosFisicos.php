<?php

namespace App\RiskEvaluations;

use App\Enums\RiskLevelEnum;
use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class DanosFisicos implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, ?UserTest $userTest = null): array
    {
        $riskSeverity = 3;

        // Probabilidade
        $accidents = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'accidents';
        })->first();

        if($accidents && $accidents->value > 50){
            $probability = 3;
        } else {
            $probability = 2;
        }

        $riskLevel = 1;

        if (!($average >= 4)) {
            $allAnswersBelowCondition = true;

            foreach ($risk->relatedQuestions as $riskQuestion) {
                // $averageAnswers = $userTest ? $userTest->answers->firstWhere('question_id', $riskQuestion['question_Id'])->value : $riskQuestion->average_value;
                $averageAnswers = 3;
    
                if ($averageAnswers >= 4) {
                    $allAnswersBelowCondition = false;
                    break;
                }
            }
        }

        $riskLevel = RiskService::calculateRiskLevel($probability, $riskSeverity);
        
        if ($allAnswersBelowCondition) {
            $riskLevel--;
        }

        return compact('probability', 'riskLevel', 'riskSeverity');
    }
}

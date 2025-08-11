<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class Afastamentos implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, ?UserTest $userTest = null): array
    {
        $riskSeverity = 3;

        // Probabilidade
        $absences = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'absences';
        })->first();

        if($absences && $absences->value > 75){
            $probability = 4;
        } else {
            $probability = 3;
        }

        $riskLevel = 1;
        
        if (!($average >= 3)) {
            $allAnswersBelowCondition = true;
        
            foreach ($risk->relatedQuestions as $riskQuestion) {
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

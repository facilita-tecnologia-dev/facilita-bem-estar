<?php

namespace App\RiskEvaluations;

use App\Enums\RiskLevelEnum;
use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class FaltaRecursos implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, ?UserTest $userTest = null): array
    {
        $riskSeverity = 2;

        $probability = RiskService::calculateProbability($average, 1, 2, true);   

        $riskLevel = 1;
        $allAnswersBelowCondition = false;
        
        
        if (!($average >= 3)) {
            $allAnswersBelowCondition = true;

            foreach ($risk->relatedQuestions as $riskQuestion) {
                if(session('company')->id === 1){
                    $averageAnswers =  $riskQuestion->average_value;
                } else{
                    $averageAnswers = $userTest ? $userTest->answers->firstWhere('question_id', $riskQuestion['question_Id'])->value : $riskQuestion->average_value;
                }
                
                if (!($averageAnswers <= 2)) {
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

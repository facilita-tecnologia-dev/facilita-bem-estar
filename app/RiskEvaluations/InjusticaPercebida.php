<?php

namespace App\RiskEvaluations;

use App\Enums\RiskLevelEnum;
use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class InjusticaPercebida implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, ?UserTest $userTest = null): array
    {
        $riskSeverity = 2;

        $probability = RiskService::calculateProbability($average, 1, 2);

        $riskLevel = 1;

        if (!($average >= 3)) {
            $allAnswersBelowCondition = true;

            foreach ($risk->relatedQuestions as $riskQuestion) {
                $averageAnswers = $userTest ? $userTest->answers->firstWhere('question_id', $riskQuestion['question_Id'])->value : $riskQuestion->average_value;
                $parentQuestionStatement = $riskQuestion['parent_question_statement'];
    
                if ($riskQuestion['parent_question_statement'] == 'Os gestores desta organização se consideram insubstituíveis') {
                    if (!($averageAnswers >= 4)) {
                        $allAnswersBelowCondition = false;
                        break;
                    }
                }
    
                if ($riskQuestion['parent_question_statement'] == 'Existem oportunidades semelhante de ascensão para todas as pessoas') {
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

<?php

namespace App\RiskEvaluations;

use App\Enums\RiskLevelEnum;
use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class ConflitosGestao implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics): array
    {
        $riskSeverity = 2;

        $probability = RiskService::calculateProbability($average, 2, 3);

        $riskLevel = 1;

        if (!($average >= 3.5)) {
            $allAnswersBelowCondition = true;

            foreach ($risk->relatedQuestions as $riskQuestion) {
                $averageAnswers = $riskQuestion->average_value;
    
                if ($riskQuestion['parent_question_statement'] == 'Em meu trabalho, incentiva-se a idolatria dos chefes') {
                    if (!($averageAnswers >= 4)) {
                        $allAnswersBelowCondition = false;
                        break;
                    }
                }
    
                if ($riskQuestion['parent_question_statement'] == 'Os gestores se preocupam com o bem estar dos trabalhadores') {
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

<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class ConflitosGestao implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, $average, Collection $metrics)
    {
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }
        
        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion->related_question_answer;
            $parentQuestionStatement = $riskQuestion->parent_question_statement;

            if ($parentQuestionStatement == 'Em meu trabalho, incentiva-se a idolatria dos chefes') {
                if ($answer >= 4) {
                    $riskPoints++;
                }
            }

            if ($parentQuestionStatement == 'Os gestores se preocupam com o bem estar dos trabalhadores') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }
        }

        return $riskPoints;
    }
}

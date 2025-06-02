<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class ConflitosGestao implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, int $testSeverity): float|int
    {
        $riskLevel = 1;

        if (!$average >= 3.5) {
            return $riskLevel;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];
            $parentQuestionStatement = $riskQuestion['parent_question_statement'];

            if ($parentQuestionStatement == 'Em meu trabalho, incentiva-se a idolatria dos chefes') {
                if (!$answer >= 4) {
                    return $riskLevel;
                }
            }

            if ($parentQuestionStatement == 'Os gestores se preocupam com o bem estar dos trabalhadores') {
                if (!$answer <= 2) {
                    return $riskLevel;
                }
            }
        }

        $probability = RiskService::calculateProbability($average);

        if($testSeverity < 2){
            return $riskLevel;
        }

        $riskLevel = match (true) {
            $probability == 2 && $testSeverity == 2 => 2,
            $probability == 3 && $testSeverity == 2 => 3,
            default => 1,
        };

        return $riskLevel;
    }
}

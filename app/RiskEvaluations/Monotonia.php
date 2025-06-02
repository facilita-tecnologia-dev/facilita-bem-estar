<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class Monotonia implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, int $testSeverity): float|int
    {
        $riskLevel = 1;

        if (!$average >= 2.5) {
            return $riskLevel;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];
            $parentQuestionStatement = $riskQuestion['parent_question_statement'];

            if ($parentQuestionStatement == 'As tarefas que executo em meu trabalho são variadas') {
                if (!$answer <= 2) {
                    return $riskLevel;
                }
            }
        }

        $probability = RiskService::calculateProbability($average);

        if($testSeverity < 1){
            return $riskLevel;
        }

        $riskLevel = match (true) {
            ($probability == 1 && $testSeverity == 1) || ($probability == 2 && $testSeverity == 1) => 1,
            default => 1,
        };

        return $riskLevel;
    }
}

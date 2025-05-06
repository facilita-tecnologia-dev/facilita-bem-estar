<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class InjusticaPercebida implements RiskEvaluatorInterface
{
    /**
     * @param Collection<int, \App\Models\Metric> $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics) : float | int
    {
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];
            $parentQuestionStatement = $riskQuestion['parent_question_statement'];

            if ($parentQuestionStatement == 'Os gestores desta organização se consideram insubstituíveis') {
                if ($answer >= 4) {
                    $riskPoints++;
                }
            }

            if ($parentQuestionStatement == 'Existem oportunidades semelhante de ascensão para todas as pessoas') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }
        }

        return $riskPoints;
    }
}

<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class FaltaRecursos implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics): float|int
    {
        $riskPoints = 0;

        if ($average <= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];
            $parentQuestionStatement = $riskQuestion['parent_question_statement'];

            if ($parentQuestionStatement == 'Os recursos de trabalho são em número suficiente para a realização das tarefas') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }

            if ($parentQuestionStatement == 'Os equipamentos são adequados para a realização das tarefas') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }
        }

        return $riskPoints;
    }
}

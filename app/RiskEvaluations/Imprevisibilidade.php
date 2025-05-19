<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class Imprevisibilidade implements RiskEvaluatorInterface
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

            if ($parentQuestionStatement == 'Há clareza na definição das tarefas') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }

            if ($parentQuestionStatement == 'As informações de que preciso para executar minhas tarefas são claras') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }
        }

        return $riskPoints;
    }
}

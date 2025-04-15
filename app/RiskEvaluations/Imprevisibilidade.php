<?php

namespace App\RiskEvaluations;

class Imprevisibilidade implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics, $questions): array
    {
        $riskPoints = 0;

        if ($average <= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->question_Id];
            $parentQuestion = $questions->where('id', $risk->question_Id)->first();

            if ($parentQuestion->statement == 'Há clareza na definição das tarefas') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }

            if ($parentQuestion->statement == 'As informações de que preciso para executar minhas tarefas são claras') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }
        }

        return [
            'riskPoints' => $riskPoints,
        ];
    }
}

<?php

namespace App\RiskEvaluations;

class FaltaRecursos implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics, $questions): array
    {
        $riskPoints = 0;

        if ($average <= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $parentQuestion = $questions->where('id', $risk->question_Id)->first();
            $answer = $answers[$risk->question_Id];
            if ($parentQuestion->statement == 'Os recursos de trabalho são em número suficiente para a realização das tarefas') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }

            if ($parentQuestion->statement == 'Os equipamentos são adequados para a realização das tarefas') {
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

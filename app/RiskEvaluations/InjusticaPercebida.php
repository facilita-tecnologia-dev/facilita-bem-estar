<?php

namespace App\RiskEvaluations;

class InjusticaPercebida implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics, $questions): array
    {
        $riskPoints = 0;

        if ($average >= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->question_Id];
            $parentQuestion = $questions->where('id', $risk->question_Id)->first();

            if ($parentQuestion->statement == 'Os gestores desta organização se consideram insubstituíveis') {
                if ($answer >= 4) {
                    $riskPoints++;
                }
            }

            if ($parentQuestion->statement == 'Existem oportunidades semelhante de ascensão para todas as pessoas') {
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

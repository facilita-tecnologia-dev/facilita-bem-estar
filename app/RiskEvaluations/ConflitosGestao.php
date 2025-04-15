<?php

namespace App\RiskEvaluations;

class ConflitosGestao implements RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics, $questions): array
    {
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }
        foreach ($risk->relatedQuestions as $risk) {
            $parentQuestion = $questions->where('id', $risk->question_Id)->first();
            $answer = $answers[$risk->question_Id];
            if ($parentQuestion->statement == 'Em meu trabalho, incentiva-se a idolatria dos chefes') {
                if ($answer >= 4) {
                    $riskPoints++;
                }
            }

            if ($parentQuestion->statement == 'Os gestores se preocupam com o bem estar dos trabalhadores') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }
        }

        if ($riskPoints > 2) {
            $evaluatedRisk = 'Risco Alto';
        } elseif ($riskPoints > 1) {
            $evaluatedRisk = 'Risco Médio';
        } else {
            $evaluatedRisk = 'Risco Baixo';
        }

        return [

            'riskPoints' => $riskPoints,
        ];
    }
}

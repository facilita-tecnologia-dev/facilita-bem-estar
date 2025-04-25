<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class ConflitosGestao implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, array $answers, $average, Collection $metrics, Collection $questions)
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

        return $riskPoints;
    }
}

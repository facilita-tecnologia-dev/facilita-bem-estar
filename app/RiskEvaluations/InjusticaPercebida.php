<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class InjusticaPercebida implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, array $answers, $average, Collection $metrics, Collection $questions)
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

        return $riskPoints;
    }
}

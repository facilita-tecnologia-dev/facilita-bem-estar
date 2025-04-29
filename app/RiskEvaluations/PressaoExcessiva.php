<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class PressaoExcessiva implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, $average, Collection $metrics)
    {
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion->related_question_answer;
            $parentQuestionStatement = $riskQuestion->parent_question_statement;

            if ($parentQuestionStatement == 'Os gestores desta organização fazem qualquer coisa para chamar a atenção') {
                if ($answer >= 4) {
                    $riskPoints++;
                }
            }

            if ($parentQuestionStatement == 'Há forte controle do trabalho') {

                if ($answer >= 4) {
                    $riskPoints++;
                }
            }
        }

        $extraHours = $metrics->filter(function ($companyMetric) {
            return $companyMetric->metricType && $companyMetric->metricType->key_name === 'extra-hours';
        })->first();

        if ($extraHours && $extraHours->value > 50) {
            if ($riskPoints <= 2) {
                $riskPoints++;
            }
        }

        return $riskPoints;
    }
}

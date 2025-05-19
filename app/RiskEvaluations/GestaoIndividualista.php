<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class GestaoIndividualista implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics): float|int
    {
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];
            $parentQuestionStatement = $riskQuestion['parent_question_statement'];

            if ($parentQuestionStatement == 'Aqui os gestores preferem trabalhar individualmente') {
                if ($answer >= 4) {
                    $riskPoints++;
                }
            }

            if ($parentQuestionStatement == 'O trabalho coletivo é valorizado pelos gestores') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }
        }

        $extraHours = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'extra-hours';
        })->first();

        if ($extraHours && $extraHours['value'] > 50) {
            if ($riskPoints <= 2) {
                $riskPoints++;
            }
        }

        return $riskPoints;
    }
}

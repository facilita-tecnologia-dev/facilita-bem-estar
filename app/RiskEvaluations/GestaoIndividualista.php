<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class GestaoIndividualista implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, array $answers, $average, Collection $metrics, Collection $questions)
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average >= 3.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $parentQuestion = $questions->where('id', $risk->question_Id)->first();
            $answer = $answers[$risk->question_Id];

            if ($parentQuestion->statement == 'Aqui os gestores preferem trabalhar individualmente') {
                if ($answer >= 4) {
                    $riskPoints++;
                }
            }

            if ($parentQuestion->statement == 'O trabalho coletivo é valorizado pelos gestores') {
                if ($answer <= 2) {
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

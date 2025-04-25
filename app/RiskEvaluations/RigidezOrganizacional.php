<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class RigidezOrganizacional implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, array $answers, $average, Collection $metrics, Collection $questions)
    {
        $evaluatedRisk = '';
        $riskPoints = 0;

        if ($average < 3.5) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->question_Id];
            $parentQuestion = $questions->where('id', $risk->question_Id)->first();

            if ($parentQuestion->statement == 'Tenho autonomia para realizar as tarefas como julgo melhor') {

                if ($answer <= 2) {
                    $riskPoints++;
                }
            }

            if ($parentQuestion->statement == 'Tenho liberdade para opinar sobre o meu trabalho') {

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

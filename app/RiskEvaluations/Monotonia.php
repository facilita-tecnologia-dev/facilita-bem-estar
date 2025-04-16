<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class Monotonia implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, array $answers, $average, Collection $metrics, Collection $questions)
    {
        $riskPoints = 0;

        if ($average <= 2.5) {
            $riskPoints += 1.5;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->question_Id];
            $parentQuestion = $questions->where('id', $risk->question_Id)->first();

            if ($parentQuestion->statement == 'As tarefas que executo em meu trabalho são variadas') {
                if ($answer <= 2) {
                    $riskPoints += 1.5;
                }
            }
        }

        $turnover = $metrics->filter(function ($companyMetric) {
            return $companyMetric->metricType && $companyMetric->metricType->key_name === 'turnover';
        })->first();

        if ($turnover && $turnover->value < 20) {
            if ($riskPoints <= 2) {
                $riskPoints++;
            }
        }

        return $riskPoints;
    }
}

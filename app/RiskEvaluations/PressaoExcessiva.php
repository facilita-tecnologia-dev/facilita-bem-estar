<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class PressaoExcessiva implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, int $testSeverity): float|int
    {
        $riskLevel = 1;

        if (!$average >= 3.5) {
            return $riskLevel;
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $riskQuestion['related_question_answer'];
            $parentQuestionStatement = $riskQuestion['parent_question_statement'];

            if ($parentQuestionStatement == 'Os gestores desta organização fazem qualquer coisa para chamar a atenção') {
                if (!$answer >= 4) {
                    return $riskLevel;
                }
            }

            if ($parentQuestionStatement == 'Há forte controle do trabalho') {

                if (!$answer >= 4) {
                    return $riskLevel;
                }
            }
        }

        $turnover = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'turnover';
        })->first();

        if($turnover->value !== 'null'){
            if($turnover->value > 50){
                $probability = 3;
            } else {
                $probability = 2;
            }
        } else{        
            $probability = RiskService::calculateProbability($average);
        }

        if($testSeverity < 3){
            return $riskLevel;
        }
        
        $riskLevel = match (true) {
            ($probability == 3 && $testSeverity == 3) || ($probability == 2 && $testSeverity == 3) => 3,
            default => 1,
        };

        return $riskLevel;
    }
}

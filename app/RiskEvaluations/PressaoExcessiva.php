<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class PressaoExcessiva implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(UserTest $userTest, Risk $risk, float $average, Collection $metrics): array
    {
        $riskSeverity = 3;

        $turnover = $metrics->filter(function ($companyMetric) {
            return $companyMetric['metricType'] && $companyMetric['metricType']['key_name'] === 'turnover';
        })->first();

        if($turnover->value > 50){
            $probability = 3;
        } else {
            $probability = 2;
        }

        $riskLevel = 1;

        if (!$average >= 3.5) {
            return [
                'riskLevel' => $riskLevel,
                'riskSeverity' => $riskSeverity,
                'probability' => $probability,
            ];
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $userTest->answers->firstWhere('question_id', $riskQuestion['question_Id'])['related_option_value'];
            $parentQuestionStatement = $riskQuestion['parent_question_statement'];

            if ($parentQuestionStatement == 'Os gestores desta organização fazem qualquer coisa para chamar a atenção') {
                if (!$answer >= 4) {
                    return [
                        'riskLevel' => $riskLevel,
                        'riskSeverity' => $riskSeverity,
                        'probability' => 1,
                    ];
                }
            }

            if ($parentQuestionStatement == 'Há forte controle do trabalho') {

                if (!$answer >= 4) {
                    return [
                        'riskLevel' => $riskLevel,
                        'riskSeverity' => $riskSeverity,
                        'probability' => 1,
                    ];
                }
            }
        }
        
        $riskLevel = RiskService::calculateRiskLevel($probability, $riskSeverity);
        
        return compact('probability', 'riskLevel', 'riskSeverity');
    }
}

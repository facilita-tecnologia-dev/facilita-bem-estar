<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class ConflitoPapeis implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(UserTest $userTest, Risk $risk, float $average, Collection $metrics): array
    {
        $riskSeverity = 2;

        // Probabilidade
        $probability = RiskService::calculateProbability($average, 1, 2, true);
        
        $riskLevel = 1;
        
        if ($average >= 3) {
            return [
                'riskLevel' => $riskLevel,
                'riskSeverity' => $riskSeverity,
                'probability' => $probability,
            ];
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $answer = $userTest->answers->firstWhere('question_id', $riskQuestion['question_Id'])['related_option_value'];

            if (!($answer <= 2)) {
                return [
                    'riskLevel' => $riskLevel,
                    'riskSeverity' => $riskSeverity,
                    'probability' => 1,
                ];
            }
        }

        $riskLevel = RiskService::calculateRiskLevel($probability, $riskSeverity);

        return compact('probability', 'riskLevel', 'riskSeverity');
    }
}

<?php

namespace App\RiskEvaluations;

use App\Enums\RiskLevelEnum;
use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class FaltaReconhecimento implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics): array
    {
        $riskSeverity = 1;

        $probability = RiskService::calculateProbability($average, 1, 2);

        $riskLevel = 1;

        if (!$average >= 3) {
            return [
                'riskLevel' => $riskLevel,
                'riskSeverity' => $riskSeverity,
                'probability' => $probability,
            ];
        }

        foreach ($risk->relatedQuestions as $riskQuestion) {
            $averageAnswers = $riskQuestion->average_value;

            if (!$averageAnswers <= 2) {
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

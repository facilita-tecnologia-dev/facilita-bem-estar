<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Models\UserTest;
use App\Services\RiskService;
use Illuminate\Support\Collection;

class ConflitosGestao implements RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(UserTest $userTest, Risk $risk, float $average, Collection $metrics): array
    {
        $riskSeverity = 2;

        $probability = RiskService::calculateProbability($average, 2, 3);

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

            if ($parentQuestionStatement == 'Em meu trabalho, incentiva-se a idolatria dos chefes') {
                if (!$answer >= 4) {
                    return [
                        'riskLevel' => $riskLevel,
                        'riskSeverity' => $riskSeverity,
                        'probability' => 1,
                    ];
                }
            }

            if ($parentQuestionStatement == 'Os gestores se preocupam com o bem estar dos trabalhadores') {
                if (!$answer <= 2) {
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

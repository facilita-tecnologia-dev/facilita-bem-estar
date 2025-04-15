<?php

namespace App\Handlers\PsychosocialRisks;

use App\Models\Test;
use App\Services\RiskEvaluatorService;

class WorkContextHandler
{
    public function __construct(private RiskEvaluatorService $riskEvaluatorService) {}

    public function process(Test $test, array $answers, $questions, $metrics, $risks): array
    {
        $score = array_sum($answers);
        $average = $score / count($answers);

        $risksList = [];

        foreach ($test->risks as $risk) {
            $handler = $this->riskEvaluatorService->getRiskEvaluatorHandler($risk);
            $evaluatedRisk = $handler->evaluateRisk($risk, $answers, $average, $metrics, $questions);
            $risksList[$risk->name] = $evaluatedRisk;
        }

        if ($average >= 3.7) {
            $severityTitle = 'Risco Baixo';
            $severityColor = 1;
        } elseif ($average >= 2.3) {
            $severityTitle = 'Risco Médio';
            $severityColor = 3;
        } else {
            $severityTitle = 'Risco Alto';
            $severityColor = 5;
        }

        return [
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
            'risks' => $risksList,
        ];
    }
}

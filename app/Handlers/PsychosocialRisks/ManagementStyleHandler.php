<?php

namespace App\Handlers\PsychosocialRisks;

use App\Models\Test;
use App\Services\RiskEvaluatorService;

class ManagementStyleHandler
{
    public function __construct(private RiskEvaluatorService $riskEvaluatorService) {}

    public function process(Test $test, array $answers, $questions, $metrics, $risks): array
    {
        $score = array_sum($answers);
        $average = $score / count($answers);

        $risksList = [];
        foreach ($risks as $risk) {
            $handler = $this->riskEvaluatorService->getRiskEvaluatorHandler($risk);
            $evaluatedRisk = $handler->evaluateRisk($risk, $answers, $average, $metrics, $questions);
            $risksList[$risk->name] = $evaluatedRisk;
        }

        if ($average >= 3.5) {
            $severityTitle = 'Estilo gerencialista';
            $severityColor = 5;
        } elseif ($average >= 2.5) {
            $severityTitle = 'Equilíbrio';
            $severityColor = 3;
        } else {
            $severityTitle = 'Estilo coletivista';
            $severityColor = 1;
        }

        return [
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
            'risks' => $risksList,
        ];
    }
}

<?php

namespace App\Handlers\PsychosocialRisks;
use App\Handlers\TestHandlerInterface;

use App\Helpers\Helper;
use App\Models\Test;
use App\Services\RiskEvaluatorService;

class WorkContextHandler implements TestHandlerInterface
{
    public function __construct(private RiskEvaluatorService $riskEvaluatorService) {}

    public function process(array $answers, $testInfo): array
    {
        $score = array_sum($answers);
        $average = $score / count($answers);

        $testType = Test::where('id', $testInfo->id)->with('questions')->first();

        $risks = Helper::getTestRisks($testType);
        $metrics = session('company')->metrics()->with('metricType');

        $risksList = [];

        foreach ($risks as $risk) {
            $handler = $this->riskEvaluatorService->getRiskEvaluatorHandler($risk);
            $evaluatedRisk = $handler->evaluateRisk($risk, $answers, $average, $metrics);
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
            'answers' => $answers,
            'score' => $score,
            'average' => number_format($average, 2),
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
            'risks' => $risksList,
        ];
    }
}

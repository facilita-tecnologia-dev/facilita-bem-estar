<?php

namespace App\Handlers\PsychosocialRisks;

use App\Enums\SeverityEnum;
use App\Models\UserTest;
use App\Services\RiskEvaluatorService;
use Illuminate\Support\Collection;

class WorkProblemsHandler
{
    public function __construct(private RiskEvaluatorService $riskEvaluatorService) {}

    public function process(UserTest $userTest, Collection $metrics): array
    {
        $average = $userTest['answers_sum'] / $userTest['answers_count'];

        $risksList = [];

        foreach ($userTest['testType']['risks'] as $risk) {
            $handler = $this->riskEvaluatorService->getRiskEvaluatorHandler($risk);
            $evaluatedRisk = $handler->evaluateRisk($risk, $average, $metrics);
            $risksList[$risk->name]['riskPoints'] = $evaluatedRisk;
            $risksList[$risk->name]['controlActions'] = $risk->controlActions;
        }

        if ($average >= 3.7) {
            $severityTitle = 'Risco Alto';
            $severityColor = SeverityEnum::CRITICO->value;
            $severityKey = 5;
        } elseif ($average >= 2.3) {
            $severityTitle = 'Risco Médio';
            $severityColor = SeverityEnum::MEDIO->value;
            $severityKey = 3;
        } else {
            $severityTitle = 'Risco Baixo';
            $severityColor = SeverityEnum::MINIMO->value;
            $severityKey = 1;
        }

        return [
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
            'severity_key' => $severityKey,
            'risks' => $risksList,
        ];
    }
}

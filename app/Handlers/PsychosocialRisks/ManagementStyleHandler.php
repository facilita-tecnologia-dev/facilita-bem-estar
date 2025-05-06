<?php

namespace App\Handlers\PsychosocialRisks;

use App\Enums\SeverityEnum;
use App\Models\UserTest;
use App\Services\RiskEvaluatorService;
use Illuminate\Support\Collection;

class ManagementStyleHandler
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

        if ($average >= 3.5) {
            $severityTitle = 'Estilo gerencialista';
            $severityColor = SeverityEnum::CRITICO->value;
            $severityKey = 5;
        } elseif ($average >= 2.5) {
            $severityTitle = 'Equilíbrio';
            $severityColor = SeverityEnum::MEDIO->value;
            $severityKey = 3;
        } else {
            $severityTitle = 'Estilo coletivista';
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

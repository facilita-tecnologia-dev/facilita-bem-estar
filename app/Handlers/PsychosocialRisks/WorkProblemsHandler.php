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
        $answersToArray = [];

        foreach ($userTest->answers as $answer) {
            $question = $userTest->testType->questions->where('id', $answer->question_id)->first();
            $answersToArray[$question->id] = $answer->relatedOption->value;
        }

        $score = array_sum($answersToArray);
        $average = $score / count($answersToArray);

        $risksList = [];

        foreach ($userTest->testType->risks as $risk) {
            $handler = $this->riskEvaluatorService->getRiskEvaluatorHandler($risk);
            $evaluatedRisk = $handler->evaluateRisk($risk, $answersToArray, $average, $metrics, $userTest->testType->questions);
            $risksList[$risk->name]['riskPoints'] = $evaluatedRisk;
            $risksList[$risk->name]['controlActions'] = $risk->controlActions;
        }

        if ($average >= 3.7) {
            $severityTitle = 'Risco Alto';
            $severityColor = SeverityEnum::CRITICO->value;
        } elseif ($average >= 2.3) {
            $severityTitle = 'Risco Médio';
            $severityColor = SeverityEnum::MEDIO->value;
        } else {
            $severityTitle = 'Risco Baixo';
            $severityColor = SeverityEnum::MINIMO->value;
        }

        return [
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
            'risks' => $risksList,
        ];
    }
}

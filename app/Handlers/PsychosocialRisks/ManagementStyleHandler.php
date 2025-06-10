<?php

namespace App\Handlers\PsychosocialRisks;

use App\Enums\ProbabilityEnum;
use App\Enums\RiskSeverityEnum;
use App\Enums\SeverityEnum;
use App\Models\CustomTest;
use App\Models\Risk;
use App\Models\Test;
use App\Models\UserTest;
use Illuminate\Support\Collection;
use App\Models\UserCustomTest;
use App\Services\RiskService;

class ManagementStyleHandler
{
    public function process(Test $testType, UserTest | CustomTest $userTest, Collection $metrics): array
    {

        $risksList = RiskService::evaluateRisks($testType, $metrics);
        $testScore = $this->calculateScore($userTest, $userTest['average_value']);

        return [
            'severity_title' => $testScore['severityTitle'],
            'severity_color' => $testScore['severityColor'],
            'severity_key' => $testScore['severityKey'],
            'risks' => $risksList,
        ];
    }

    public function calculateScore($userTest, $average)
    {
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
            'severityTitle' => $severityTitle,
            'severityColor' => $severityColor,
            'severityKey' => $severityKey,
        ];
    }
}

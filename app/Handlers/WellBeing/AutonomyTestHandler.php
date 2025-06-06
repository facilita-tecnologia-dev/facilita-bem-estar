<?php

namespace App\Handlers\WellBeing;

use App\Enums\SeverityEnum;
use App\Handlers\TestHandlerInterface;
use App\Models\Test;

class AutonomyTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 29) {
            $severityTitle = 'Alta Autonomia';
            $severityColor = SeverityEnum::MINIMO->value;
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Autonomia Moderada';
            $severityColor = SeverityEnum::MEDIO->value;
        } else {
            $severityTitle = 'Baixa Autonomia';
            $severityColor = SeverityEnum::CRITICO->value;
        }

        return [
            'answers' => $answers,
            'total_points' => $totalPoints,
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
            'recommendations' => $this->getRecommendations($severityColor),
        ];
    }

    private function getRecommendations(string $severityColor): array
    {
        $recommendations = [
            1 => ['Capacidade de autogestão significativa'],
            3 => ['Necessidade de melhorias na flexibilidade'],
            5 => ['Indica pouca liberdade e flexibilidade no trabalho'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

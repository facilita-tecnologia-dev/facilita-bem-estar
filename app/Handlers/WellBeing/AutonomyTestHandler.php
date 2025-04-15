<?php

namespace App\Handlers\WellBeing;

use App\Handlers\TestHandlerInterface;

class AutonomyTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 29) {
            $severityTitle = 'Alta Autonomia';
            $severityColor = 1;
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Autonomia Moderada';
            $severityColor = 3;
        } else {
            $severityTitle = 'Baixa Autonomia';
            $severityColor = 5;
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

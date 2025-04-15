<?php

namespace App\Handlers\WellBeing;

use App\Handlers\TestHandlerInterface;

class PressureForResultsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 33) {
            $severityTitle = 'Pressão Crítica';
            $severityColor = 5;
        } elseif ($totalPoints >= 25) {
            $severityTitle = 'Alta Pressão';
            $severityColor = 4;
        } elseif ($totalPoints >= 17) {
            $severityTitle = 'Pressão Moderada';
            $severityColor = 3;
        } else {
            $severityTitle = 'Baixa Pressão';
            $severityColor = 1;
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
            5 => ['Buscar suporte da gestão'],
            4 => ['Propor discussões sobre cultura organizacional'],
            3 => ['Manter atenção nas metas e resultados previstos'],
            1 => ['Manter práticas existentes'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

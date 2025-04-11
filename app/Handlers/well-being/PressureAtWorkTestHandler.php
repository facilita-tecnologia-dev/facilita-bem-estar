<?php

namespace App\Handlers\WellBeing;
use App\Handlers\TestHandlerInterface;

class PressureAtWorkTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 29) {
            $severityTitle = 'Alta Pressão';
            $severityColor = 5;
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
            5 => ['Buscar suporte organizacional'],
            3 => ['Implementar gestão de tempo'],
            1 => ['Implementar gestão de tempo'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

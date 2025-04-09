<?php

namespace App\Handlers;

class StressTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 27) {
            $severityTitle = 'Alto Estresse';
            $severityColor = 5;
        } elseif ($totalPoints >= 14) {
            $severityTitle = 'Estresse Moderado';
            $severityColor = 3;
        } else {
            $severityTitle = 'Baixo Estresse';
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
            5 => ['Indica possível necessidade de intervenção ou estratégias de gestão de estresse'],
            3 => ['Indica algumas dificuldades no manejo de situações estressantes'],
            1 => ['Sugere boa capacidade de gerenciamento de situações estressantes'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

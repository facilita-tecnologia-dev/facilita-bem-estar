<?php

namespace App\Handlers;

class AnxietyTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 15) {
            $severityTitle = 'Grave';
            $severityColor = 5;
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Moderada';
            $severityColor = 3;
        } elseif ($totalPoints >= 5) {
            $severityTitle = 'Leve';
            $severityColor = 2;
        } else {
            $severityTitle = 'Mínima';
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
            5 => ['Grave'],
            3 => ['Moderada'],
            2 => ['Leve'],
            1 => ['Mínima'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

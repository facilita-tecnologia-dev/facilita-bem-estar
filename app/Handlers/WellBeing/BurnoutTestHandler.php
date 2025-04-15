<?php

namespace App\Handlers\WellBeing;

use App\Handlers\TestHandlerInterface;

class BurnoutTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 27) {
            $severityTitle = 'Zona de Risco';
            $severityColor = 5;
        } elseif ($totalPoints >= 14) {
            $severityTitle = 'Zona de Alerta';
            $severityColor = 3;
        } else {
            $severityTitle = 'Zona de Bem-estar';
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
            5 => ['Necessidade urgente de intervenção'],
            3 => ['Recomenda-se atenção e estratégias de prevenção'],
            1 => ['Recursos pessoais bem gerenciados'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

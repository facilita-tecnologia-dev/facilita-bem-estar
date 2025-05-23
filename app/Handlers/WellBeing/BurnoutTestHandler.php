<?php

namespace App\Handlers\WellBeing;

use App\Enums\SeverityEnum;
use App\Handlers\TestHandlerInterface;
use App\Models\Test;

class BurnoutTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 27) {
            $severityTitle = 'Zona de Risco';
            $severityColor = SeverityEnum::CRITICO->value;
        } elseif ($totalPoints >= 14) {
            $severityTitle = 'Zona de Alerta';
            $severityColor = SeverityEnum::MEDIO->value;
        } else {
            $severityTitle = 'Zona de Bem-estar';
            $severityColor = SeverityEnum::MINIMO->value;
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

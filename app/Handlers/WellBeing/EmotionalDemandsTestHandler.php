<?php

namespace App\Handlers\WellBeing;

use App\Enums\SeverityEnum;
use App\Handlers\TestHandlerInterface;

class EmotionalDemandsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 33) {
            $severityTitle = 'Alta exigência emocional';
            $severityColor = SeverityEnum::CRITICO->value;
        } elseif ($totalPoints >= 21) {
            $severityTitle = 'Média exigência emocional';
            $severityColor = SeverityEnum::MEDIO->value;
        } else {
            $severityTitle = 'Baixa exigência emocional';
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
            5 => ['Grande necessidade de controle e supressão emocional'],
            3 => ['Necessidade intermediária de regulação emocional'],
            1 => ['Baixa necessidade de controle emocional'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

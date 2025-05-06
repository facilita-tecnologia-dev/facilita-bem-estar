<?php

namespace App\Handlers\WellBeing;

use App\Enums\SeverityEnum;
use App\Handlers\TestHandlerInterface;
use App\Models\Test;

class StressTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 27) {
            $severityTitle = 'Alto Estresse';
            $severityColor = SeverityEnum::CRITICO->value;
        } elseif ($totalPoints >= 14) {
            $severityTitle = 'Estresse Moderado';
            $severityColor = SeverityEnum::MEDIO->value;
        } else {
            $severityTitle = 'Baixo Estresse';
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
            5 => ['Indica possível necessidade de intervenção ou estratégias de gestão de estresse'],
            3 => ['Indica algumas dificuldades no manejo de situações estressantes'],
            1 => ['Sugere boa capacidade de gerenciamento de situações estressantes'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

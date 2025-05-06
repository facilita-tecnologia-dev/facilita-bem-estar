<?php

namespace App\Handlers\WellBeing;

use App\Enums\SeverityEnum;
use App\Handlers\TestHandlerInterface;
use App\Models\Test;

class PressureForResultsTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 33) {
            $severityTitle = 'Pressão Crítica';
            $severityColor = SeverityEnum::CRITICO->value;
        } elseif ($totalPoints >= 25) {
            $severityTitle = 'Alta Pressão';
            $severityColor = SeverityEnum::ALTO->value;
        } elseif ($totalPoints >= 17) {
            $severityTitle = 'Pressão Moderada';
            $severityColor = SeverityEnum::MEDIO->value;
        } else {
            $severityTitle = 'Baixa Pressão';
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
            5 => ['Buscar suporte da gestão'],
            4 => ['Propor discussões sobre cultura organizacional'],
            3 => ['Manter atenção nas metas e resultados previstos'],
            1 => ['Manter práticas existentes'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

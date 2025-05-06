<?php

namespace App\Handlers\WellBeing;

use App\Enums\SeverityEnum;
use App\Handlers\TestHandlerInterface;
use App\Models\Test;

class SocialRelationsTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 15) {
            $severityTitle = 'Baixo Risco';
            $severityColor = SeverityEnum::MINIMO->value;
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Risco Moderado';
            $severityColor = SeverityEnum::MEDIO->value;
        } elseif ($totalPoints >= 5) {
            $severityTitle = 'Alto Risco';
            $severityColor = SeverityEnum::ALTO->value;
        } else {
            $severityTitle = 'Risco Crítico';
            $severityColor = SeverityEnum::CRITICO->value;
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
            1 => ['Relações interpessoais muito positivas'],
            3 => ['Algumas áreas necessitam de pequenos ajustes'],
            4 => ['Necessidade de intervenções urgentes'],
            5 => ['Requer reestruturação imediata das dinâmicas sociais'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

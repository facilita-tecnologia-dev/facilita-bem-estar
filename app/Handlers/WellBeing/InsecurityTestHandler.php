<?php

namespace App\Handlers\WellBeing;

use App\Enums\SeverityEnum;
use App\Handlers\TestHandlerInterface;
use App\Models\Test;

class InsecurityTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 33) {
            $severityTitle = 'Alta Insegurança';
            $severityColor = SeverityEnum::CRITICO->value;
        } elseif ($totalPoints >= 21) {
            $severityTitle = 'Insegurança Moderada';
            $severityColor = SeverityEnum::MEDIO->value;
        } else {
            $severityTitle = 'Baixa Insegurança';
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
            5 => ['Nível alto de insegurança'],
            3 => ['Nível médio de insegurança'],
            1 => ['Nível baixo de insegurança'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

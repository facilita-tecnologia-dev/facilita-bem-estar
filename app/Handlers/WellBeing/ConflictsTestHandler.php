<?php

namespace App\Handlers\WellBeing;

use App\Enums\SeverityEnum;
use App\Handlers\TestHandlerInterface;
use App\Models\Test;

class ConflictsTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 33) {
            $severityTitle = 'Alto Nível de Conflitos';
            $severityColor = SeverityEnum::CRITICO->value;
        } elseif ($totalPoints >= 21) {
            $severityTitle = 'Conflitos Moderados';
            $severityColor = SeverityEnum::MEDIO->value;
        } else {
            $severityTitle = 'Baixo Nível de Conflitos';
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
            5 => ['Treinamento em comunicação não-violenta'],
            3 => ['Considere a reestruturação das dinâmicas de equipe'],
            1 => ['Ambiente de trabalho relativamente harmonioso'],
        ];

        return $recommendations[$severityColor] ?? [];
    }
}

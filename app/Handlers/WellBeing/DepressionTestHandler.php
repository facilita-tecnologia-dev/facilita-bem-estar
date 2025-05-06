<?php

namespace App\Handlers\WellBeing;

use App\Enums\SeverityEnum;
use App\Handlers\TestHandlerInterface;
use App\Models\Test;

class DepressionTestHandler implements TestHandlerInterface
{
    public function process(Test $testInfo, array $answers, $questions): array
    {
        $totalPoints = array_sum($answers);

        if ($totalPoints >= 20) {
            $severityTitle = 'Grave';
            $severityColor = SeverityEnum::CRITICO->value;
        } elseif ($totalPoints >= 15) {
            $severityTitle = 'Moderadamente grave';
            $severityColor = SeverityEnum::ALTO->value;
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Moderada';
            $severityColor = SeverityEnum::MEDIO->value;
        } elseif ($totalPoints >= 5) {
            $severityTitle = 'Leve';
            $severityColor = SeverityEnum::BAIXO->value;
        } else {
            $severityTitle = 'Mínima';
            $severityColor = SeverityEnum::MINIMO->value;
        }

        // $suicidalThoughts = isset($answers[9]) && $answers[9] > 0;

        return [
            'answers' => $answers,
            'total_points' => $totalPoints,
            'severity_title' => $severityTitle,
            // 'suicidal_risk' => $suicidalThoughts,
            'severity_color' => $severityColor,
            'recommendations' => $this->getRecommendations($severityColor),
        ];
    }

    private function getRecommendations(string $severityColor): array
    {

        $baseRecommendations = [
            5 => ['Consultar um profissional imediatamente'],
            4 => ['Consultar um profissional logo'],
            3 => ['Consultar um profissional'],
            2 => ['Considerar conversar com um profissional'],
            1 => ['Nenhuma medida recomendada'],
        ];

        $recommendations = $baseRecommendations[$severityColor] ?? [];

        // if ($suicidalThoughts) {
        //     array_unshift($recommendations, 'Buscar ajuda imediata');
        // }

        return $recommendations;
    }
}

<?php

namespace App\Handlers;

class DepressionTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 20) {
            $severityTitle = 'Grave';
            $severityColor = 5;
        } elseif ($totalPoints >= 15) {
            $severityTitle = 'Moderadamente grave';
            $severityColor = 4;
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
        
        $suicidalThoughts = isset($answers[9]) && $answers[9] > 0;
        
        return [
            'answers' => $answers,
            'totalPoints' => $totalPoints,
            'severityTitle' => $severityTitle,
            'suicidal_risk' => $suicidalThoughts,
            'severityColor' => $severityColor,
            'recommendations' => $this->getRecommendations($severityColor, $suicidalThoughts)
        ];
    }
    
    private function getRecommendations(string $severityColor, bool $suicidalThoughts): array
    {

        $baseRecommendations = [
            5 => ['Consultar um profissional imediatamente'],
            4 => ['Consultar um profissional logo'],
            3 => ['Consultar um profissional'],
            2 => ['Considerar conversar com um profissional'],
            1 => ['Nenhuma medida recomendada']
        ];
        
        $recommendations = $baseRecommendations[$severityColor] ?? [];
        
        if ($suicidalThoughts) {
            array_unshift($recommendations, 'Buscar ajuda imediata');
        }
        
        return $recommendations;
    }
}
<?php

namespace App\Handlers;

class DepressionTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 20) {
            $severityTitle = 'Grave';
            $severityColor = 'red';
        } elseif ($totalPoints >= 15) {
            $severityTitle = 'Moderadamente grave';
            $severityColor = 'orange';
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Moderada';
            $severityColor = 'yellow';
        } elseif ($totalPoints >= 5) {
            $severityTitle = 'Leve';
            $severityColor = 'blue';
        } else {
            $severityTitle = 'Mínima';
            $severityColor = 'green';
        }
        
        $suicidalThoughts = isset($answers[9]) && $answers[9] > 0;
        
        return [
            'answers' => $answers,
            'totalPoints' => $totalPoints,
            'severityTitle' => $severityTitle,
            'suicidal_risk' => $suicidalThoughts,
            'severityColor' => $severityColor,
            'recommendations' => $this->getRecommendations($severityTitle, $suicidalThoughts)
        ];
    }
    
    private function getRecommendations(string $severityTitle, bool $suicidalThoughts): array
    {

        $baseRecommendations = [
            'Grave' => ['Consultar um profissional imediatamente'],
            'Moderadamente grave' => ['Consultar um profissional logo'],
            'Moderada' => ['Consultar um profissional'],
            'Leve' => ['Considerar conversar com um profissional'],
            'Mínima' => ['Nenhuma medida recomendada']
        ];
        
        $recommendations = $baseRecommendations[$severityTitle] ?? [];
        
        if ($suicidalThoughts) {
            array_unshift($recommendations, 'Buscar ajuda imediata');
        }
        
        return $recommendations;
    }
}
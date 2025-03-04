<?php

namespace App\Handlers;

class DepressionTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 20) {
            $severity = 'Grave';
            $color = 'red';
        } elseif ($totalPoints >= 15) {
            $severity = 'Moderadamente grave';
            $color = 'orange';
        } elseif ($totalPoints >= 10) {
            $severity = 'Moderada';
            $color = 'yellow';
        } elseif ($totalPoints >= 5) {
            $severity = 'Leve';
            $color = 'blue';
        } else {
            $severity = 'Mínima';
            $color = 'green';
        }
        
        $suicidalThoughts = isset($answers[9]) && $answers[9] > 0;
        
        return [
            'answers' => $answers,
            'totalPoints' => $totalPoints,
            'severity' => $severity,
            'suicidal_risk' => $suicidalThoughts,
            'color' => $color,
            'recommendations' => $this->getRecommendations($severity, $suicidalThoughts)
        ];
    }
    
    private function getRecommendations(string $severity, bool $suicidalThoughts): array
    {

        $baseRecommendations = [
            'Grave' => ['Consultar um profissional imediatamente'],
            'Moderadamente grave' => ['Consultar um profissional logo'],
            'Moderada' => ['Consultar um profissional'],
            'Leve' => ['Considerar conversar com um profissional'],
            'Mínima' => ['Nenhuma medida recomendada']
        ];
        
        $recommendations = $baseRecommendations[$severity] ?? [];
        
        if ($suicidalThoughts) {
            array_unshift($recommendations, 'Buscar ajuda imediata');
        }
        
        return $recommendations;
    }
}
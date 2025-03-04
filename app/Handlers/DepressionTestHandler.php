<?php

namespace App\Handlers;

class DepressionTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 20) {
            $severity = 'alto';
        } elseif ($totalPoints >= 15) {
            $severity = 'médio-alto';
        } elseif ($totalPoints >= 10) {
            $severity = 'medio';
        } elseif ($totalPoints >= 5) {
            $severity = 'baixo';
        } else {
            $severity = 'mínimo';
        }
        
        $suicidalThoughts = isset($answers[9]) && $answers[9] > 0;
        
        return [
            'answers' => $answers,
            'totalPoints' => $totalPoints,
            'severity' => $severity,
            'suicidal_risk' => $suicidalThoughts,
            'recommendations' => $this->getRecommendations($severity, $suicidalThoughts)
        ];
    }
    
    private function getRecommendations(string $severity, bool $suicidalThoughts): array
    {

        $baseRecommendations = [
            'severe' => ['Consultar um profissional imediatamente'],
            'moderately_severe' => ['Consultar um profissional logo'],
            'moderate' => ['Consultar um profissional'],
            'mild' => ['Considerar conversar com um profissional'],
            'minimal' => ['Nenhuma medida recomendada']
        ];
        
        $recommendations = $baseRecommendations[$severity] ?? [];
        
        if ($suicidalThoughts) {
            array_unshift($recommendations, 'Buscar ajuda imediata');
        }
        
        return $recommendations;
    }
}
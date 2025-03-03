<?php

namespace App\Handlers;

class DepressionTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 20) {
            $severity = 'severe';
        } elseif ($totalPoints >= 15) {
            $severity = 'moderately_severe';
        } elseif ($totalPoints >= 10) {
            $severity = 'moderate';
        } elseif ($totalPoints >= 5) {
            $severity = 'mild';
        } else {
            $severity = 'minimal';
        }
        
        $suicidalThoughts = isset($answers[9]) && $answers[9] > 0;
        
        return [
            'severity' => $severity,
            'suicidal_risk' => $suicidalThoughts,
            'recommendations' => $this->getRecommendations($severity, $suicidalThoughts)
        ];
    }
    
    private function getRecommendations(string $severity, bool $suicidalThoughts): array
    {

        $baseRecommendations = [
            'severe' => ['Consultar um profissional imediatamente', 'Considerar tratamento intensivo'],
            'moderately_severe' => ['Consultar um profissional logo', 'Considerar medicação', 'Terapia regular'],
            'moderate' => ['Consultar um profissional', 'Considerar terapia', 'Atividade física'],
            'mild' => ['Considerar conversar com um profissional', 'Atividade física regular', 'Boa alimentação'],
            'minimal' => ['Manter hábitos saudáveis', 'Monitorar sintomas']
        ];
        
        $recommendations = $baseRecommendations[$severity] ?? [];
        
        if ($suicidalThoughts) {
            array_unshift($recommendations, 'Buscar ajuda imediata');
        }
        
        return $recommendations;
    }
}
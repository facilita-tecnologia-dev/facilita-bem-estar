<?php

namespace App\Handlers;

class AutonomyTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 29) {
            $severity = 'Alta Autonomia';
            $color = 'green';
        } elseif ($totalPoints >= 10) {
            $severity = 'Autonomia Moderada';
            $color = 'yellow';
        } else {
            $severity = 'Baixa Autonomia';
            $color = 'red';
        }
        
        return [
            'answers' => $answers,
            'totalPoints' => $totalPoints,
            'severity' => $severity,
            'color' => $color,
            'recommendations' => $this->getRecommendations($severity)
        ];
    }
    
    private function getRecommendations(string $severity): array
    {
        $recommendations = [
            'Alta Autonomia' => ['Capacidade de autogestão significativa'],
            'Autonomia Moderada' => ['Necessidade de melhorias na flexibilidade'],
            'Baixa Autonomia' => ['Indica pouca liberdade e flexibilidade no trabalho'],
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
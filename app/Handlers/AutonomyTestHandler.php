<?php

namespace App\Handlers;

class AutonomyTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 29) {
            $severityTitle = 'Alta Autonomia';
            $severityColor = 'green';
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Autonomia Moderada';
            $severityColor = 'yellow';
        } else {
            $severityTitle = 'Baixa Autonomia';
            $severityColor = 'red';
        }
        
        return [
            'answers' => $answers,
            'totalPoints' => $totalPoints,
            'severityTitle' => $severityTitle,
            'severityColor' => $severityColor,
            'recommendations' => $this->getRecommendations($severityTitle)
        ];
    }
    
    private function getRecommendations(string $severityTitle): array
    {
        $recommendations = [
            'Alta Autonomia' => ['Capacidade de autogestão significativa'],
            'Autonomia Moderada' => ['Necessidade de melhorias na flexibilidade'],
            'Baixa Autonomia' => ['Indica pouca liberdade e flexibilidade no trabalho'],
        ];
        
        return $recommendations[$severityTitle] ?? [];
    }
}
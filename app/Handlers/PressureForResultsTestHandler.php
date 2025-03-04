<?php

namespace App\Handlers;

class PressureForResultsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 33) {
            $severity = 'Pressão Crítica';
            $color = 'red';
        } elseif ($totalPoints >= 25) {
            $severity = 'Alta Pressão';
            $color = 'orange';
        } elseif ($totalPoints >= 17) {
            $severity = 'Pressão Moderada';
            $color = 'yellow';
        } else {
            $severity = 'Baixa Pressão';
            $color = 'green';
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
            'Pressão Crítica' => ['Buscar suporte da gestão'],
            'Alta Pressão' => ['Propor discussões sobre cultura organizacional'],
            'Pressão Moderada' => ['Manter práticas existentes'],
            'Baixa Pressão' => ['Manter práticas existentes']
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
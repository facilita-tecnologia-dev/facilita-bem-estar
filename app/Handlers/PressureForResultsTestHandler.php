<?php

namespace App\Handlers;

class PressureForResultsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 33) {
            $severityTitle = 'Pressão Crítica';
            $severityColor = 'red';
        } elseif ($totalPoints >= 25) {
            $severityTitle = 'Alta Pressão';
            $severityColor = 'orange';
        } elseif ($totalPoints >= 17) {
            $severityTitle = 'Pressão Moderada';
            $severityColor = 'yellow';
        } else {
            $severityTitle = 'Baixa Pressão';
            $severityColor = 'green';
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
            'Pressão Crítica' => ['Buscar suporte da gestão'],
            'Alta Pressão' => ['Propor discussões sobre cultura organizacional'],
            'Pressão Moderada' => ['Manter práticas existentes'],
            'Baixa Pressão' => ['Manter práticas existentes']
        ];
        
        return $recommendations[$severityTitle] ?? [];
    }
}
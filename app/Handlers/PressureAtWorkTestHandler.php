<?php

namespace App\Handlers;

class PressureAtWorkTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 29) {
            $severityTitle = 'Alta Pressão';
            $severityColor = 'red';
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
            'Alta Pressão' => ['Buscar suporte organizacional'],
            'Pressão Moderada' => ['Implementar gestão de tempo'],
            'Baixa Pressão' => ['Implementar gestão de tempo'],
        ];
        
        return $recommendations[$severityTitle] ?? [];
    }
}
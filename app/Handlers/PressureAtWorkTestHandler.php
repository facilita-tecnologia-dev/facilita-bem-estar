<?php

namespace App\Handlers;

class PressureAtWorkTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 29) {
            $severity = 'Alta Pressão';
            $color = 'red';
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
            'Alta Pressão' => ['Buscar suporte organizacional'],
            'Pressão Moderada' => ['Implementar gestão de tempo'],
            'Baixa Pressão' => ['Implementar gestão de tempo'],
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
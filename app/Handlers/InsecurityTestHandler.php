<?php

namespace App\Handlers;

class InsecurityTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 33) {
            $severity = 'Alta Insegurança';
            $color = 'red';
        } elseif ($totalPoints >= 21) {
            $severity = 'Insegurança Moderada';
            $color = 'yellow';
        } else {
            $severity = 'Baixa Insegurança';
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
            'Alta Insegurança' => ['Nível alto de insegurança'],
            'Insegurança Moderada' => ['Nível médio de insegurança'],
            'Baixa Insegurança' => ['Nível baixo de insegurança'],
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
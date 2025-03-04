<?php

namespace App\Handlers;

class AnxietyTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 15) {
            $severity = 'alto';
        } elseif ($totalPoints >= 10) {
            $severity = 'médio';
        } elseif ($totalPoints >= 5) {
            $severity = 'baixo';
        } else {
            $severity = 'mínimo';
        }
        
        return [
            'answers' => $answers,
            'totalPoints' => $totalPoints,
            'severity' => $severity,
            'recommendations' => $this->getRecommendations($severity)
        ];
    }
    
    private function getRecommendations(string $severity): array
    {
        $recommendations = [
            'severe' => ['Consultar um profissional imediatamente'],
            'moderate' => ['Considere consultar um profissional'],
            'mild' => ['Você está um pouco ansioso',],
            'minimal' => ['Você está quase nada ansioso']
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
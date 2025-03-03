<?php

namespace App\Handlers;

class AnxietyTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 15) {
            $severity = 'severe';
        } elseif ($totalPoints >= 10) {
            $severity = 'moderate';
        } elseif ($totalPoints >= 5) {
            $severity = 'mild';
        } else {
            $severity = 'minimal';
        }
        
        return [
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
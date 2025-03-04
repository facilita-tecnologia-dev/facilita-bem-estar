<?php

namespace App\Handlers;

class StressTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 27) {
            $severity = 'Alto Estresse';
            $color = 'red';
        } elseif ($totalPoints >= 14) {
            $severity = 'Estresse Moderado';
             $color = 'yellow';
        } else {
            $severity = 'Baixo Estresse';
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
            'Alto Estresse' => ['Indica possível necessidade de intervenção ou estratégias de gestão de estresse'],
            'Estresse Moderado' => ['Indica algumas dificuldades no manejo de situações estressantes'],
            'Baixo Estresse' => ['Sugere boa capacidade de gerenciamento de situações estressantes']
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
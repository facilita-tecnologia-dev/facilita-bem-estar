<?php

namespace App\Handlers;

class StressTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 27) {
            $severityTitle = 'Alto Estresse';
            $severityColor = 'red';
        } elseif ($totalPoints >= 14) {
            $severityTitle = 'Estresse Moderado';
             $severityColor = 'yellow';
        } else {
            $severityTitle = 'Baixo Estresse';
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
            'Alto Estresse' => ['Indica possível necessidade de intervenção ou estratégias de gestão de estresse'],
            'Estresse Moderado' => ['Indica algumas dificuldades no manejo de situações estressantes'],
            'Baixo Estresse' => ['Sugere boa capacidade de gerenciamento de situações estressantes']
        ];
        
        return $recommendations[$severityTitle] ?? [];
    }
}
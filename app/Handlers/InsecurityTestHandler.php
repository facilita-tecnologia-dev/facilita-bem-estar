<?php

namespace App\Handlers;

class InsecurityTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 33) {
            $severityTitle = 'Alta Insegurança';
            $severityColor = 'red';
        } elseif ($totalPoints >= 21) {
            $severityTitle = 'Insegurança Moderada';
            $severityColor = 'yellow';
        } else {
            $severityTitle = 'Baixa Insegurança';
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
            'Alta Insegurança' => ['Nível alto de insegurança'],
            'Insegurança Moderada' => ['Nível médio de insegurança'],
            'Baixa Insegurança' => ['Nível baixo de insegurança'],
        ];
        
        return $recommendations[$severityTitle] ?? [];
    }
}
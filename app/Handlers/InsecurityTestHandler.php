<?php

namespace App\Handlers;

class InsecurityTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 33) {
            $severityTitle = 'Alta Insegurança';
            $severityColor = 5;
        } elseif ($totalPoints >= 21) {
            $severityTitle = 'Insegurança Moderada';
            $severityColor = 3;
        } else {
            $severityTitle = 'Baixa Insegurança';
            $severityColor = 1;
        }
        
        return [
            'answers' => $answers,
            'total_points' => $totalPoints,
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
            'recommendations' => $this->getRecommendations($severityColor)
        ];
    }
    
    private function getRecommendations(string $severityColor): array
    {
        $recommendations = [
            5 => ['Nível alto de insegurança'],
            3 => ['Nível médio de insegurança'],
            1 => ['Nível baixo de insegurança'],
        ];
        
        return $recommendations[$severityColor] ?? [];
    }
}
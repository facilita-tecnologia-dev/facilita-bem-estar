<?php

namespace App\Handlers;

class SocialRelationsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 15) {
            $severityTitle = 'Baixo Risco';
            $severityColor = 1;
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Risco Moderado';
            $severityColor = 3;
        } elseif ($totalPoints >= 5) {
            $severityTitle = 'Alto Risco';
            $severityColor = 4;
        } else {
            $severityTitle = 'Risco Crítico';
            $severityColor = 5;
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
            1 => ['Relações interpessoais muito positivas'],
            3 => ['Algumas áreas necessitam de pequenos ajustes'],
            4 => ['Necessidade de intervenções urgentes'],
            5 => ['Requer reestruturação imediata das dinâmicas sociais']
        ];
        
        return $recommendations[$severityColor] ?? [];
    }
}
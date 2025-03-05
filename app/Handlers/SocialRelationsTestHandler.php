<?php

namespace App\Handlers;

class SocialRelationsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 15) {
            $severityTitle = 'Baixo Risco';
            $severityColor = 'green';
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Risco Moderado';
            $severityColor = 'yellow';
        } elseif ($totalPoints >= 5) {
            $severityTitle = 'Alto Risco';
            $severityColor = 'orange';
        } else {
            $severityTitle = 'Risco Crítico';
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
            'Baixo Risco' => ['Relações interpessoais muito positivas'],
            'Risco Moderado' => ['Algumas áreas necessitam de pequenos ajustes'],
            'Alto Risco' => ['Necessidade de intervenções urgentes'],
            'Risco Crítico' => ['Requer reestruturação imediata das dinâmicas sociais']
        ];
        
        return $recommendations[$severityTitle] ?? [];
    }
}
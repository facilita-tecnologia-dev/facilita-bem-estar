<?php

namespace App\Handlers;

class SocialRelationsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 15) {
            $severity = 'Baixo Risco';
            $color = 'green';
        } elseif ($totalPoints >= 10) {
            $severity = 'Risco Moderado';
            $color = 'yellow';
        } elseif ($totalPoints >= 5) {
            $severity = 'Alto Risco';
            $color = 'orange';
        } else {
            $severity = 'Risco Crítico';
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
            'Baixo Risco' => ['Relações interpessoais muito positivas'],
            'Risco Moderado' => ['Algumas áreas necessitam de pequenos ajustes'],
            'Alto Risco' => ['Necessidade de intervenções urgentes'],
            'Risco Crítico' => ['Requer reestruturação imediata das dinâmicas sociais']
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
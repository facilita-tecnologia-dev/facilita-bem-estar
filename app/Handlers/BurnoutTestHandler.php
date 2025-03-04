<?php

namespace App\Handlers;

class BurnoutTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 27) {
            $severity = 'Zona de Risco - Alto Burnout';  
            $color = 'red';
        } elseif ($totalPoints >= 14) {
            $severity = 'Zona de Alerta - Burnout Moderado';
            $color = 'yellow';
        } else {
            $severity = 'Zona de Bem-estar - Baixo Burnout';
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
            'Zona de Risco - Alto Burnout' => ['Necessidade urgente de intervenção'],
            'Zona de Alerta - Burnout Moderado' => ['Recomenda-se atenção e estratégias de prevenção'],
            'Zona de Bem-estar - Baixo Burnout' => ['Recursos pessoais bem gerenciados']
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
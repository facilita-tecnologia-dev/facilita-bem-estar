<?php

namespace App\Handlers;

class BurnoutTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 27) {
            $severityTitle = 'Zona de Risco - Alto Burnout';  
            $severityColor = 'red';
        } elseif ($totalPoints >= 14) {
            $severityTitle = 'Zona de Alerta - Burnout Moderado';
            $severityColor = 'yellow';
        } else {
            $severityTitle = 'Zona de Bem-estar - Baixo Burnout';
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
            'Zona de Risco - Alto Burnout' => ['Necessidade urgente de intervenção'],
            'Zona de Alerta - Burnout Moderado' => ['Recomenda-se atenção e estratégias de prevenção'],
            'Zona de Bem-estar - Baixo Burnout' => ['Recursos pessoais bem gerenciados']
        ];
        
        return $recommendations[$severityTitle] ?? [];
    }
}
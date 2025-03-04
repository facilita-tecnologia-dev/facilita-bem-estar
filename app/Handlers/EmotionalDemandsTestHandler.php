<?php

namespace App\Handlers;

class EmotionalDemandsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 33) {
            $severity = 'Alta exigência emocional';
            $color = 'red';
        } elseif ($totalPoints >= 21) {
            $severity = 'Média exigência emocional';
            $color = 'yellow';
        } else {
            $severity = 'Baixa exigência emocional';
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
            'Alta exigência emocional' => ['Grande necessidade de controle e supressão emocional'],
            'Média exigência emocional' => ['Necessidade intermediária de regulação emocional'],
            'Baixa exigência emocional' => ['Baixa necessidade de controle emocional',],
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
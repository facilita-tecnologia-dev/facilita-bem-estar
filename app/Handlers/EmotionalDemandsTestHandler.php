<?php

namespace App\Handlers;

class EmotionalDemandsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 33) {
            $severityTitle = 'Alta exigência emocional';
            $severityColor = 'red';
        } elseif ($totalPoints >= 21) {
            $severityTitle = 'Média exigência emocional';
            $severityColor = 'yellow';
        } else {
            $severityTitle = 'Baixa exigência emocional';
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
            'Alta exigência emocional' => ['Grande necessidade de controle e supressão emocional'],
            'Média exigência emocional' => ['Necessidade intermediária de regulação emocional'],
            'Baixa exigência emocional' => ['Baixa necessidade de controle emocional',],
        ];
        
        return $recommendations[$severityTitle] ?? [];
    }
}
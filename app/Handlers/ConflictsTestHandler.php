<?php

namespace App\Handlers;

class ConflictsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 33) {
            $severityTitle = 'Alto Nível de Conflitos';
            $severityColor = 5;
        } elseif ($totalPoints >= 21) {
            $severityTitle = 'Conflitos Moderados';
            $severityColor = 3;
        } else {
            $severityTitle = 'Baixo Nível de Conflitos';
            $severityColor = 1;
        }
        
        return [
            'answers' => $answers,
            'totalPoints' => $totalPoints,
            'severityTitle' => $severityTitle,
            'severityColor' => $severityColor,
            'recommendations' => $this->getRecommendations($severityColor),
        ];
    }
    
    private function getRecommendations(string $severityColor): array
    {
        $recommendations = [
            5 => ['Treinamento em comunicação não-violenta'],
            3 => ['Considere a reestruturação das dinâmicas de equipe'],
            1 => ['Ambiente de trabalho relativamente harmonioso'],
        ];
        
        return $recommendations[$severityColor] ?? [];
    }
}
<?php

namespace App\Handlers;

class ConflictsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 33) {
            $severityTitle = 'Alto Nível de Conflitos';
            $severityColor = 'red';
        } elseif ($totalPoints >= 21) {
            $severityTitle = 'Conflitos Moderados';
            $severityColor = 'yellow';
        } else {
            $severityTitle = 'Baixo Nível de Conflitos';
            $severityColor = 'green';
        }
        
        return [
            'answers' => $answers,
            'totalPoints' => $totalPoints,
            'severityTitle' => $severityTitle,
            'severityColor' => $severityColor,
            'recommendations' => $this->getRecommendations($severityTitle),
        ];
    }
    
    private function getRecommendations(string $severityTitle): array
    {
        $recommendations = [
            'Alto Nível de Conflitos' => ['Treinamento em comunicação não-violenta'],
            'Conflitos Moderados' => ['Considere a reestruturação das dinâmicas de equipe'],
            'Baixo Nível de Conflitos' => ['Ambiente de trabalho relativamente harmonioso'],
        ];
        
        return $recommendations[$severityTitle] ?? [];
    }
}
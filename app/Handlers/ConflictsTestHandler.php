<?php

namespace App\Handlers;

class ConflictsTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 33) {
            $severity = 'Alto Nível de Conflitos';
            $color = 'red';
        } elseif ($totalPoints >= 21) {
            $severity = 'Conflitos Moderados';
            $color = 'yellow';
        } else {
            $severity = 'Baixo Nível de Conflitos';
            $color = 'green';
        }
        
        return [
            'answers' => $answers,
            'totalPoints' => $totalPoints,
            'severity' => $severity,
            'color' => $color,
            'recommendations' => $this->getRecommendations($severity),
        ];
    }
    
    private function getRecommendations(string $severity): array
    {
        $recommendations = [
            'Alto Nível de Conflitos' => ['Treinamento em comunicação não-violenta'],
            'Conflitos Moderados' => ['Considere a reestruturação das dinâmicas de equipe'],
            'Baixo Nível de Conflitos' => ['Ambiente de trabalho relativamente harmonioso'],
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
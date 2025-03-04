<?php

namespace App\Handlers;

class AnxietyTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 15) {
            $severity = 'Grave';
            $color = 'red';
        } elseif ($totalPoints >= 10) {
            $severity = 'Moderada';
            $color = 'yellow';
        } elseif ($totalPoints >= 5) {
            $severity = 'Leve';
            $color = 'blue';
        } else {
            $severity = 'Mínima';
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
            'Grave' => ['Grave'],
            'Moderada' => ['Moderada'],
            'Leve' => ['Leve'],
            'Mínima' => ['Mínima']
        ];
        
        return $recommendations[$severity] ?? [];
    }
}
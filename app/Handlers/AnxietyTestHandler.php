<?php

namespace App\Handlers;

class AnxietyTestHandler implements TestHandlerInterface
{
    public function process(array $answers): array
    {
        $totalPoints = array_sum($answers);
        
        if ($totalPoints >= 15) {
            $severityTitle = 'Grave';
            $severityColor = 'red';
        } elseif ($totalPoints >= 10) {
            $severityTitle = 'Moderada';
            $severityColor = 'yellow';
        } elseif ($totalPoints >= 5) {
            $severityTitle = 'Leve';
            $severityColor = 'blue';
        } else {
            $severityTitle = 'Mínima';
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
            'Grave' => ['Grave'],
            'Moderada' => ['Moderada'],
            'Leve' => ['Leve'],
            'Mínima' => ['Mínima']
        ];
        
        return $recommendations[$severityTitle] ?? [];
    }
}
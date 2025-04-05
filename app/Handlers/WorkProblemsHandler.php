<?php

namespace App\Handlers;

use Illuminate\Support\Facades\DB;

class WorkProblemsHandler implements TestHandlerInterface
{
    public function process(array $answers, $testInfo): array
    {
        $totalPoints = array_sum($answers);
        $average = $totalPoints / count($answers);

        $questions = DB::table('test_questions')->where('test_type_id', $testInfo->id)->get();
        
        $factorDivision = [];

        foreach($answers as $questionId => $answer){
            $question = $questions->where('id', $questionId)->first();
            $factorDivision[$question->factor][] = $answer;
        }
        
        foreach($factorDivision as $key => $factor){
            $factorDivision[$key] = array_sum($factor) / count($factor);
        }

        if ($average >= 3.7) {
            $severityTitle = 'Risco Alto';
            $severityColor = 5;
        } elseif ($average >= 2.3) {
            $severityTitle = 'Risco Médio';
            $severityColor = 3;
        } else {
            $severityTitle = 'Risco Baixo';
            $severityColor = 1;
        }
        
        return [
            'answers' => $answers,
            'total_points' => $totalPoints,
            'average' => number_format($average, 2),
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
            // 'recommendations' => $this->getRecommendations($severityColor)
        ];
    }
    
    // private function getRecommendations(string $severityColor): array
    // {
    //     $recommendations = [
    //         5 => ['Grave'],
    //         3 => ['Moderada'],
    //         2 => ['Leve'],
    //         1 => ['Mínima']
    //     ];
        
    //     return $recommendations[$severityColor] ?? [];
    // }
}
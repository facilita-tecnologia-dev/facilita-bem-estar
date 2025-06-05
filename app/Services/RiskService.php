<?php

namespace App\Services;

use App\Models\Risk;
use App\RiskEvaluations\RiskEvaluatorFactory;
use App\RiskEvaluations\RiskEvaluatorInterface;

class RiskService
{
    public static function getRiskEvaluatorHandler(Risk $risk): RiskEvaluatorInterface
    {
        $handler = RiskEvaluatorFactory::getRiskEvaluator($risk);

        return $handler;
    }

    public static function calculateProbability(float $average, int $min = null, int $max = null, bool $inverted = false)
    {
        $probability = $inverted ? 4 : 1;

        
        if($average > 3.5){
            $probability = $inverted ? 1 : 4;
        } elseif($average >= 3){
            $probability = $inverted ? 2 : 3;
        } elseif($average >= 2.5){
            $probability = $inverted ? 3 : 2;
        }

        // Aplica os limites mínimo e máximo
        if($min && $max){
            $probability = max($min, min($probability, $max));
        }

        return $probability;
    }

    public static function calculateRiskQuestionAverage($userTest, Risk $risk): int
    {
        $mappedRiskQuestions = $risk->relatedQuestions->map(function($question) use($userTest) {
            $isInverted = $question->parent_question_inverted;
            $answer = $userTest->answers->firstWhere('question_id', $question['question_Id'])['related_option_value'];

            if($isInverted){
                if($answer > 3.5){
                    return 1;
                } else{
                    return 0;
                }
            } else{
                if(!($answer > 3.5)){
                    return 1;
                } else{
                    return 0;
                }
            }
        });

    
        $hasRisk = array_reduce($mappedRiskQuestions->toArray(), function ($acc, $value) {
            return $acc * $value;
        }, 1);

        return $hasRisk;
    }

    public static function calculateRiskLevel(int $probability, int $severity, int $min = null, int $max = null): string
    {
        // Matriz de riscos
        $matrix = [
            1 => [1 => 1, 2 => 1, 3 => 2, 4 => 2],
            2 => [1 => 1, 2 => 2, 3 => 3, 4 => 3],
            3 => [1 => 2, 2 => 3, 3 => 3, 4 => 4],
            4 => [1 => 2, 2 => 3, 3 => 4, 4 => 4],
        ];

        // Ordem de risco
        $order = [1, 2, 3, 4];

        // Valor atual
        $risco = $matrix[$probability][$severity];

        // Normalizar se necessário
        $indiceAtual = array_search($risco, $order);

        if ($min !== null) {
            $indiceMinimo = array_search($min, $order);
            if ($indiceAtual < $indiceMinimo) {
                return $order[$indiceMinimo];
            }
        }

        if ($max !== null) {
            $indiceMaximo = array_search($max, $order);
            if ($indiceAtual > $indiceMaximo) {
                return $order[$indiceMaximo];
            }
        }

        return $risco;
    } 
}

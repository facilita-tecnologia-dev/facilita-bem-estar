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

    public static function calculateProbability(float $average)
    {
        $probability = 1;

        if($average > 3.5){
            $probability = 4;
        } elseif($average >= 3){
            $probability = 3;
        } elseif($average >= 2.5){
            $probability = 2;
        }

        return $probability;
    }
}

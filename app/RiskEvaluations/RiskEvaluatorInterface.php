<?php

namespace App\RiskEvaluations;

interface RiskEvaluatorInterface
{
    public function evaluateRisk($risk, $answers, $average, $metrics, $questions): array;
}

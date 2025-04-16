<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

interface RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, array $answers, $average, Collection $metrics, Collection $questions);
}

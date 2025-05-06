<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

interface RiskEvaluatorInterface
{
    /**
     * @param Collection<int, \App\Models\Metric> $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics) : float | int;
}

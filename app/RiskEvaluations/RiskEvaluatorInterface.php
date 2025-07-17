<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Models\UserTest;
use Illuminate\Support\Collection;

interface RiskEvaluatorInterface
{
    /**
     * @param  Collection<int, \App\Models\Metric>  $metrics
     */
    public function evaluateRisk(Risk $risk, float $average, Collection $metrics, ?UserTest $userTest = null): array;
}

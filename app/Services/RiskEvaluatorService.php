<?php

namespace App\Services;

use App\Models\Risk;
use App\RiskEvaluations\RiskEvaluatorFactory;

class RiskEvaluatorService
{
    public function __construct(private RiskEvaluatorFactory $riskEvaluatorFactory) {}

    public function getRiskEvaluatorHandler(Risk $risk)
    {
        $handler = $this->riskEvaluatorFactory->getRiskEvaluator($risk);

        return $handler;
    }
}

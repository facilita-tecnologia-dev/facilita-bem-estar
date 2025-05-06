<?php

namespace App\Services;

use App\Models\Risk;
use App\RiskEvaluations\RiskEvaluatorFactory;
use App\RiskEvaluations\RiskEvaluatorInterface;

class RiskEvaluatorService
{
    public function __construct(private RiskEvaluatorFactory $riskEvaluatorFactory) {}

    public function getRiskEvaluatorHandler(Risk $risk) : RiskEvaluatorInterface
    {
        $handler = $this->riskEvaluatorFactory->getRiskEvaluator($risk);

        return $handler;
    }
}

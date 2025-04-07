<?php 

namespace App\Services;


use App\Handlers\TestHandlerFactory;
use App\Models\PendingTestAnswer;
use App\Models\Risk;
use App\Models\TestType;
use App\RiskEvaluations\RiskEvaluatorFactory;
use Illuminate\Support\Facades\Auth;

class RiskEvaluatorService
{
    public function __construct(private RiskEvaluatorFactory $riskEvaluatorFactory)
    {
    }

    public function getRiskEvaluatorHandler(Risk $risk){
        $handler = $this->riskEvaluatorFactory->getRiskEvaluator($risk);
        return $handler;
    }
}
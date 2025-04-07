<?php

namespace App\Handlers;

use App\Helpers\Helper;
use App\Models\TestType;
use App\Services\RiskEvaluatorService;
use Illuminate\Support\Facades\DB;

class ManagementStyleHandler implements TestHandlerInterface
{
    public function __construct(private RiskEvaluatorService $riskEvaluatorService){}

    public function process(array $answers, $testInfo): array
    {
        $score = array_sum($answers);
        $average = $score / count($answers);

        $testType = TestType::where('id', $testInfo->id)->with('questions')->first();
        
        $risks = Helper::getTestRisks($testType);
        
        $risksList = [];
        
        foreach($risks as $risk){
            $handler = $this->riskEvaluatorService->getRiskEvaluatorHandler($risk);
            $evaluatedRisk = $handler->evaluateRisk($risk, $answers, $average);
            $risksList[$risk->name] = $evaluatedRisk;
        }        
        
        if ($average >= 3.5) {
            $severityTitle = 'Estilo gerencialista';
            $severityColor = 5;
        } elseif ($average >= 2.5) {
            $severityTitle = 'Equilíbrio';
            $severityColor = 3;
        } else {
            $severityTitle = 'Estilo coletivista';
            $severityColor = 1;
        }
        
        return [
            'answers' => $answers,
            'score' => $score,
            'average' => number_format($average, 2),
            'severity_title' => $severityTitle,
            'severity_color' => $severityColor,
            'risks' => $risksList,
        ];
    }
}
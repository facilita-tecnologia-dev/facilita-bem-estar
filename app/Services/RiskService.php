<?php

namespace App\Services;

use App\Enums\ProbabilityEnum;
use App\Enums\RiskSeverityEnum;
use App\Models\ActionPlan;
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

    public static function evaluateRisks($testType, $metrics)
    {
        $risksList = [];

        foreach ($testType['risks'] as $risk) {
            $handler = RiskService::getRiskEvaluatorHandler($risk);
            $evaluatedRisk = $handler->evaluateRisk($risk, $testType['average'], $metrics);
            
            $risksList[$risk->name]['riskLevel'] = $evaluatedRisk['riskLevel'];
            $risksList[$risk->name]['probability'] = ProbabilityEnum::labelFromValue($evaluatedRisk['probability']);
            $risksList[$risk->name]['severity'] = RiskSeverityEnum::labelFromValue($evaluatedRisk['riskSeverity']);
            $risksList[$risk->name]['controlActions'] = RiskService::getControlActions($risk, $evaluatedRisk['riskLevel']);
        }

        return $risksList;
    }

    public static function evaluateIndividualTestRisks($testType, $userTest, $metrics)
    {
        $risksList = [];
        foreach ($testType['risks'] as $risk) {
            $handler = RiskService::getRiskEvaluatorHandler($risk);
            $evaluatedRisk = $handler->evaluateRisk($risk, $userTest['average_value'], $metrics, $userTest);
            
            $risksList[$risk->name]['riskLevel'] = $evaluatedRisk['riskLevel'];
            $risksList[$risk->name]['probability'] = ProbabilityEnum::labelFromValue($evaluatedRisk['probability']);
            $risksList[$risk->name]['severity'] = RiskSeverityEnum::labelFromValue($evaluatedRisk['riskSeverity']);
            $risksList[$risk->name]['controlActions'] = RiskService::getControlActions($risk, $evaluatedRisk['riskLevel']);
        }

        return $risksList;
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

    public static function getControlActions(Risk $risk, int $riskLevel)
    {
        $controlActions = session('company')['actionPlan']['controlActions']
        ->filter(function($ca) use($risk, $riskLevel) {
            return $ca['risk']['id'] == $risk->id && $ca['severity'] == $riskLevel;
        });

        return $controlActions;
    }
}

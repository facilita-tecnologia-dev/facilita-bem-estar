<?php

namespace App\Handlers\PsychosocialRisks;

use App\Enums\ProbabilityEnum;
use App\Enums\RiskSeverityEnum;
use App\Enums\SeverityEnum;
use App\Models\Risk;
use App\Models\UserTest;
use Illuminate\Support\Collection;
use App\Models\UserCustomTest;
use App\Services\RiskService;

class WorkExperienceHandler
{
    public function process(UserTest | UserCustomTest $userTest, Collection $metrics): array
    {
        $average = $userTest['answers_sum'] / $userTest['answers_count'];

        $testRisks = $userTest['testType']['risks']; 
        
        $testSeverity = $this->calculateTestSeverity($userTest, $testRisks);
    
        $risksList = $this->evaluateRisks($userTest, $testRisks, $average, $metrics, $testSeverity);

        $testScore = $this->calculateScore($userTest, $average);

        return [
            'severity_title' => $testScore['severityTitle'],
            'severity_color' => $testScore['severityColor'],
            'severity_key' => $testScore['severityKey'],
            'risks' => $risksList,
        ];
    }

    // public function calculateRiskQuestionAverage(Risk $risk): int
    // {
    //     $sumScore = $risk->relatedQuestions->reduce(function ($acc, $question) {
    //         return $acc + $question['related_question_answer'];
    //     }, 0);
        
    //     $average = $sumScore / $risk->relatedQuestions->count();
        
    //     if($average > 3.5){
    //         return true;
    //     }

    //     return false;
    // }

    public function calculateTestSeverity($userTest, $testRisks): int
    {
        $testSeverity = 1;
    
        foreach ($testRisks as $risk) {
            if(in_array($risk->name, ['Esgotamento Emocional', 'Ansiedade ou Estresse'])){
                $average = RiskService::calculateRiskQuestionAverage($userTest, $risk);
                if($average){$testSeverity = max($testSeverity, 3);}
            }

            if(in_array($risk->name, ['Isolamento Social', 'Frustração ou Desmotivação', 'Irritabilidade', 'Dificuldade de Concentração'])){
                $average = RiskService::calculateRiskQuestionAverage($userTest, $risk);
                if($average){$testSeverity = max($testSeverity, 2);}
            }
        }

        return $testSeverity;
    }

    public function calculateScore($userTest, $average)
    {
        if ($average >= 3.7) {
            $severityTitle = 'Risco Alto';
            $severityColor = SeverityEnum::CRITICO->value;
            $severityKey = 5;
        } elseif ($average >= 2.3) {
            $severityTitle = 'Risco Médio';
            $severityColor = SeverityEnum::MEDIO->value;
            $severityKey = 3;
        } else {
            $severityTitle = 'Risco Baixo';
            $severityColor = SeverityEnum::MINIMO->value;
            $severityKey = 1;
        }

        return [
            'severityTitle' => $severityTitle,
            'severityColor' => $severityColor,
            'severityKey' => $severityKey,
        ];
    }

    public function evaluateRisks($userTest, $testRisks, $average, $metrics, $testSeverity)
    {
        $risksList = [];

        foreach ($testRisks as $risk) {
            $handler = RiskService::getRiskEvaluatorHandler($risk);
            $evaluatedRisk = $handler->evaluateRisk($userTest, $risk, $average, $metrics, $testSeverity);

            $risksList[$risk->name]['riskLevel'] = $evaluatedRisk['riskLevel'];
            $risksList[$risk->name]['probability'] = ProbabilityEnum::labelFromValue($evaluatedRisk['probability']);
            $risksList[$risk->name]['severity'] = RiskSeverityEnum::labelFromValue($evaluatedRisk['riskSeverity']);
            $risksList[$risk->name]['controlActions'] = RiskService::getControlActions($risk);
        }
        
        return $risksList;
    }
}

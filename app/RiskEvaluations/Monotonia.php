<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Models\TestType;
use App\RiskEvaluations\RiskEvaluatorInterface;
use Illuminate\Support\Facades\DB;

class Monotonia implements RiskEvaluatorInterface
{ 

    public function evaluateRisk($risk, $answers, $average): string
    {;
        $evaluatedRisk = '';
        $riskPoints = 0;

        if($average >= 2.5){
            $riskPoints++;
        }

        foreach($risk->questionMaps as $risk){
            if($risk->question->statement == 'As tarefas que executo em meu trabalho são variadas'){
                $answer = $answers[$risk->question->id];
                if($answer <= 2){
                    $riskPoints++;
                }
            }
        }

        if($riskPoints > 1){
            $evaluatedRisk = 'Risco Alto';
        } else if($riskPoints > 0){
            $evaluatedRisk = 'Risco Médio';
        }   else{
            $evaluatedRisk = 'Risco Baixo';
        }

        return $evaluatedRisk;
    }

}
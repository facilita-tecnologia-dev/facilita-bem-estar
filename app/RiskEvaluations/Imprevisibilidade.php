<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Models\TestType;
use App\RiskEvaluations\RiskEvaluatorInterface;
use Illuminate\Support\Facades\DB;

class Imprevisibilidade implements RiskEvaluatorInterface
{ 

    public function evaluateRisk($risk, $answers, $average): string
    {;
        $evaluatedRisk = '';
        $riskPoints = 0;

        if($average >= 3){
            $riskPoints++;
        }

        foreach($risk->questionMaps as $risk){
            if($risk->question->statement == 'Há clareza na definição das tarefas'){
                $answer = $answers[$risk->question->id];
                
                if($answer <= 2){
                    $riskPoints++;
                }
            }
            
            if($risk->question->statement == 'As informações de que preciso para executar minhas tarefas são claras'){
                $answer = $answers[$risk->question->id];

                if($answer <= 2){
                    $riskPoints++;
                }
            }
        }

        if($riskPoints > 2){
            $evaluatedRisk = 'Risco Alto';
        } else if($riskPoints > 1){
            $evaluatedRisk = 'Risco Médio';
        }   else{
            $evaluatedRisk = 'Risco Baixo';
        }

        return $evaluatedRisk;
    }

}
<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Models\TestType;
use App\RiskEvaluations\RiskEvaluatorInterface;
use Illuminate\Support\Facades\DB;

class ConflitosGestao implements RiskEvaluatorInterface
{ 

    public function evaluateRisk($risk, $answers, $average): string
    {;
        $evaluatedRisk = '';
        $riskPoints = 0;

        if($average >= 3.5){
            $riskPoints++;
        }

        foreach($risk->questionMaps as $risk){
            if($risk->question->statement == 'Em meu trabalho, incentiva-se a idolatria dos chefes'){
                $answer = $answers[$risk->question->id];
                
                if($answer >= 4){
                    $riskPoints++;
                }
            }
            
            if($risk->question->statement == 'Os gestores se preocupam com o bem estar dos trabalhadores'){
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
<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use App\Models\TestType;
use App\RiskEvaluations\RiskEvaluatorInterface;
use Illuminate\Support\Facades\DB;

class PressaoExcessiva implements RiskEvaluatorInterface
{ 

    public function evaluateRisk($risk, $answers, $average): string
    {;
        $evaluatedRisk = '';
        $riskPoints = 0;

        if($average >= 3.5){
            $riskPoints++;
        }

        foreach($risk->questionMaps as $risk){
            if($risk->question->statement == 'Os gestores desta organização fazem qualquer coisa para chamar a atenção'){
                $answer = $answers[$risk->question->id];
                
                if($answer >= 4){
                    $riskPoints++;
                }
            }
            
            if($risk->question->statement == 'Há forte controle do trabalho'){
                $answer = $answers[$risk->question->id];

                if($answer >= 4){
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
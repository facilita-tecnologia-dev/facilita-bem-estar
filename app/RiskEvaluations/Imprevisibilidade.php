<?php

namespace App\RiskEvaluations;

use App\Models\Risk;
use Illuminate\Support\Collection;

class Imprevisibilidade implements RiskEvaluatorInterface
{
    public function evaluateRisk(Risk $risk, array $answers, $average, Collection $metrics, Collection $questions)
    {
        $riskPoints = 0;

        if ($average <= 3) {
            $riskPoints++;
        }

        foreach ($risk->relatedQuestions as $risk) {
            $answer = $answers[$risk->question_Id];
            $parentQuestion = $questions->where('id', $risk->question_Id)->first();

            if ($parentQuestion->statement == 'Há clareza na definição das tarefas') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }

            if ($parentQuestion->statement == 'As informações de que preciso para executar minhas tarefas são claras') {
                if ($answer <= 2) {
                    $riskPoints++;
                }
            }
        }

        return $riskPoints;
    }
}

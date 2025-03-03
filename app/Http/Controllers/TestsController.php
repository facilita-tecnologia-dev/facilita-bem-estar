<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestsController
{
    private $testsMap = [
        [
            'test' => 'ansiedade',
            'nextTest' => 'depressao',
            'numberOfQuestions' => 7
        ],
        [
            'test' => 'depressao',
            'nextTest' => '',
            'numberOfQuestions' => 9
        ],
    ];


    public function index(string $test){
        if(!$test){
            return back();
        }

        return view('tests.'.$test);
    }

    public function handleTestSubmitted(Request $request, $test){
      // Verifica no mapa qual é o próximo teste
        $nextTest = '';
        $submittedTest = '';
        $numberOfQuestions = 0;


        // Buscando as informações no mapa
        foreach ($this->testsMap as $key => $item) {
            if($item['test'] == $test){
                $submittedTest = $item['test'];
                $numberOfQuestions = $item['numberOfQuestions'];
                $nextTest = $item['nextTest'];
            }
        }

        // Cria as regras de validação dinamicamente
        $validationRules = [];
        for ($i = 1; $i < $numberOfQuestions + 1; $i++) { 
            $validationRules['question_'.$i] = 'required';
        }


        // Valida todas as respostas
        $validatedData = $request->validate($validationRules);

        // Pega e converte as respostas do usuário em pontos
        $answers = collect($validatedData)
        ->filter(function($value, $key){
            return str_starts_with($key, 'question_');
        })
        ->mapWithKeys(function($value, $key) {
            $questionNumber = substr($key, strlen('question_'));
            return [$questionNumber => (int) $value];
        });

        // Soma os pontos de cada resposta para descobrir o total de pontos
        $testPoints = $answers->sum();

        // Guarda as informações na sessão
        session([$submittedTest.'_answers' => $answers]);
        session([$submittedTest.'_total_points'=> $testPoints]);

  
        // Redireciona para o próximo teste
        return to_route('test', $nextTest);
    }   
}

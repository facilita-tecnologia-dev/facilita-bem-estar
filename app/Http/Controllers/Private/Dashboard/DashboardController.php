<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Helpers\Helper;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use App\Models\TestType;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use PHPUnit\Event\Code\TestCollection;

class DashboardController
{
    protected $helper;
    protected $usersLatestCollections;

    public function __construct(Helper $helper)
    {  
        $this->helper = $helper; 
        $this->usersLatestCollections = $helper->getUsersLatestCollections(); 
    }

    public function __invoke(){
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }

        $generalResults = $this->getCompiledResults();
        $testsParticipation = $this->getTestsParticipation(); 

        $risks = $this->getRisks();

        
        return view('admin.dashboard.index', [
            'generalResults' => $generalResults,
            'testsParticipation' => $testsParticipation,
            'risks' => $risks
        ]);
    }

    /**
     * Retorna um array com os dados compilados de todos os testes.
     * @return array
     */
    private function getCompiledResults(): array{
        $usersLatestCollections = User::
        where('company_id', session('company')->id)
        ->has('testCollections')
        ->with('testCollections', function($query){
            $query->latest()->limit(1)
            ->with('tests', function($q){
                $q->with(['answers', 'questions', 'testType']);
            });
        })
        ->get();

        $compiledResults = [];
        
        foreach($usersLatestCollections as $user){
            if(count($user->testCollections) > 0){
                
                foreach($user->testCollections[0]->tests as $test){
                    $testName = $test->testType->display_name;
                    $severity = $test->severity_title;
                   
                    if (!isset($compiledResults[$testName][$severity])) {
                        $compiledResults[$testName][$severity] = [
                            'count' => 0,
                            'severity_color' => $test['severity_color']
                        ];
                    }
                    
                    $compiledResults[$testName][$severity]['count'] += 1;
                }
            }
            
        }

        return $compiledResults;
    }

    // private function compileTestResults(){
    //     $userTestCollections = User::
    //     where('company_id', session('company')->id)
    //     ->has('testCollections')
    //     ->with('testCollections', function($query){
    //         $query->latest()->limit(1)
    //         ->with('tests', function($q){
    //             $q->with(['answers', 'questions']);
    //         });
    //     })
    //     ->get();
    //     // $factorAverages = User::query()
    //     //     ->where('company_id', session('company')->id)
    //     //     ->whereHas('testCollections')
    //     //     ->join('test_collections', 'users.id',  '=', 'test_collections.user_id')
    //     //     ->join('test_forms',            'test_collections.id', '=', 'test_forms.test_collection_id')
    //     //     ->join('test_answers',          'test_forms.id',            '=', 'test_answers.test_form_id')
    //     //     ->join('test_questions',        'test_answers.test_question_id', '=', 'test_questions.id')

    //     //     // ✔ sub‑query escalar permitida
    //     //     ->where('test_collections.id', '=', function ($query) {
    //     //         $query->select('id')
    //     //             ->from('test_collections')
    //     //             ->whereColumn('test_collections.user_id', 'users.id')
    //     //             ->latest()      // ORDER BY created_at DESC
    //     //             ->limit(1);     // OK dentro de sub‑query escalar
    //     //     })

    //     //     ->select('test_questions.factor',
    //     //             DB::raw('AVG(test_answers.value) as average_value'))
    //     //     ->groupBy('test_questions.factor')
    //     //     ->get();

    //     // dd($factorAverages);

    //     $answersPerFactor = [];

    //     foreach ($userTestCollections as $user) {
    //         foreach ($user->testCollections as $testCollection) {
    //             foreach ($testCollection->tests as $testForm) {
    //                 $questionAnswers = [];

    //                 // Inicializa os fatores com arrays vazios
    //                 foreach ($testForm->questions as $question) {
    //                     $questionAnswers[$question->factor] = [];
    //                 }

    //                 // Agrupa as respostas por fator
    //                 foreach ($testForm->answers as $answer) {
    //                     $questionFactor = $answer->testQuestion->factor;
    //                     $questionAnswers[$questionFactor][] = $answer;
    //                 }

    //                 // Armazena as respostas agrupadas
    //                 $answersPerFactor[$testForm->test_name][] = $questionAnswers;
    //             }
    //         }
    //     }

    //     // Calcula as médias por item e coleta os valores para médias gerais
    //     $averagesPerFactor = $answersPerFactor;
    //     $factorTotals = [];

    //     foreach ($averagesPerFactor as $testName => $test) {
    //         foreach ($test as $index => $factors) {
    //             foreach ($factors as $factorName => $factor) {
    //                 $sum = 0;
    //                 $count = count($factor);

    //                 if ($count > 0) {
    //                     foreach ($factor as $answer) {
    //                         $sum += (int) $answer->value;
    //                     }
    //                     $average = $sum / $count;
    //                 } else {
    //                     $average = 0;
    //                 }

    //                 // Armazena a média no array temporário para cálculo geral
    //                 $factorTotals[$testName][$factorName][] = $average;
    //             }
    //         }
    //     }

    //     $result = [];

    //     foreach($factorTotals as $testName => $test){
    //         foreach($test as $key => $factor){
    //             $result[$testName][$key] = array_sum($factor) / count($factor);
    //         }
    //     }

    //     // foreach($result as $testName => $test){
    //     //     $result[$testName]['total'] = array_sum($test) / count($test);
    //     // }

    //     // dump($userTestCollections);
    //     return $result;
    // }

    /**
     * Retorna um array com 2 itens.
     * @return array [Total de usuários, usuários que realizaram os testes]
     */
    private function getTestsParticipation(): array{
        $usersLatestCollections = $this->usersLatestCollections;

        $usersWithCollections = [];

        foreach($usersLatestCollections as $user){
            if(count($user->testCollections) > 0){
                $usersWithCollections[] = $user;
            }
            
        }

        $countCollections = count($usersWithCollections);

        
        $users = User::query()->where('company_id', '=', session('company')->id)->get()->toArray();
        
        $countUsers = count($users);
        
        $testsParticipation = [$countCollections, ($countUsers - $countCollections)];

        return $testsParticipation;
   }

   private function getRisks(){
        $usersLatestCollections = User::query()
        ->where('company_id', '=', session('company')->id)
        ->has('testCollections')
        ->with('testCollections', function($query){
            $query->with('risks', function($q){
                $q->with('risk');
            })->with('tests');
        })
        ->latest()
        ->get();

        $risksMap = [
            'Risco Baixo' => 1,
            'Risco Médio' => 2,
            'Risco Alto' => 3
        ];

        $testRisksMap = [
            "Rigidez Organizacional" => 'Organização do Trabalho',
            "Falta de Recursos" => 'Organização do Trabalho',
            "Sobrecarga de Trabalho" => 'Organização do Trabalho',
            "Imprevisibilidade" => 'Organização do Trabalho',
            "Monotonia" => 'Organização do Trabalho',
            "Conflito de Papéis" => 'Organização do Trabalho',

            "Pressão Excessiva da Gestão" => 'Estilos de Gestão',
            "Injustiça Percebida" => 'Estilos de Gestão',
            "Falta de Suporte Gerencial" => 'Estilos de Gestão',
            "Conflitos com a Gestão" => 'Estilos de Gestão',
            "Falta de Reconhecimento" => 'Estilos de Gestão',
            "Gestão Individualista" => 'Estilos de Gestão',

            "Dificuldade de Concentração" => 'Indicadores de Sofrimento',
            "Irritabilidade" => 'Indicadores de Sofrimento',
            "Frustração ou Desmotivação" => 'Indicadores de Sofrimento',
            "Isolamento Social" => 'Indicadores de Sofrimento',
            "Ansiedade ou Estresse" => 'Indicadores de Sofrimento',
            "Esgotamento Emocional" => 'Indicadores de Sofrimento',

            "Deterioração da Vida Pessoal" => 'Danos Relacionados ao Trabalho',
            "Problemas Psicossomáticos" => 'Danos Relacionados ao Trabalho',
            "Distúrbios do Sono" => 'Danos Relacionados ao Trabalho',
            "Afastamentos Frequentes" => 'Danos Relacionados ao Trabalho',
            "Danos Psicológicos" => 'Danos Relacionados ao Trabalho',
            "Danos Físicos" => 'Danos Relacionados ao Trabalho',
        ];

        $mappedRisks = [];
        
        foreach($usersLatestCollections as $user){
            foreach($user->testCollections[0]->risks as $riskResult){
                $test = $testRisksMap[$riskResult->risk->name];
                $mappedRisks[$test][$riskResult->risk->name]['score'][] = $risksMap[$riskResult->score];
            }
        }
        
        
        foreach($mappedRisks as $testName => $test){
            foreach($test as $riskName => $risk){
                $average =  array_sum($risk['score']) / count($risk['score']);
                $mappedRisks[$testName][$riskName]['score'] = ceil($average);
                
                if($average > 2){
                    $mappedRisks[$testName][$riskName]['risk'] = "Risco Alto";
                } else if( $average > 1){
                    $mappedRisks[$testName][$riskName]['risk'] = "Risco Médio";
                } else{
                    $mappedRisks[$testName][$riskName]['risk'] = "Risco Baixo";
                }
            }
        }
        return $mappedRisks;
   }
}

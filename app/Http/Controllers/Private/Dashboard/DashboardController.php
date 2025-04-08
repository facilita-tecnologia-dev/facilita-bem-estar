<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Helpers\Helper;
use App\Models\CompanyMetric;
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
        $metrics = $this->getMetrics();

        
        return view('admin.dashboard.index', [
            'generalResults' => $generalResults,
            'testsParticipation' => $testsParticipation,
            'risks' => $risks,
            'metrics' => $metrics,
        ]);
    }

    /**
     * Retorna um array com os dados compilados de todos os testes.
     * @return array
     */
    private function getCompiledResults(): array{
        $usersLatestCollections = User::
        whereRelation('companies', 'companies.id', session('company')->id)
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

        
        $users = User::query()->whereRelation('companies', 'companies.id', session('company')->id)->get()->toArray();
        
        $countUsers = count($users);
        
        $testsParticipation = [$countCollections, ($countUsers - $countCollections)];

        return $testsParticipation;
   }

    private function getRisks(){
        $usersLatestCollections = User::
        whereRelation('companies', 'companies.id', session('company')->id)
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

            "Dificuldade de Concentração" => 'Indicadores de Adversidades',
            "Irritabilidade" => 'Indicadores de Adversidades',
            "Frustração ou Desmotivação" => 'Indicadores de Adversidades',
            "Isolamento Social" => 'Indicadores de Adversidades',
            "Ansiedade ou Estresse" => 'Indicadores de Adversidades',
            "Esgotamento Emocional" => 'Indicadores de Adversidades',

            "Deterioração da Vida Pessoal" => 'Distúrbios Relacionados ao Trabalho',
            "Problemas Psicossomáticos" => 'Distúrbios Relacionados ao Trabalho',
            "Distúrbios do Sono" => 'Distúrbios Relacionados ao Trabalho',
            "Afastamentos Frequentes" => 'Distúrbios Relacionados ao Trabalho',
            "Distúrbios Psicológicos" => 'Distúrbios Relacionados ao Trabalho',
            "Distúrbios Físicos" => 'Distúrbios Relacionados ao Trabalho',
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

   private function getMetrics(){
        $metrics = CompanyMetric::where('company_id', session('company')->id)->where('value', '!=', 'null')->with('metricType')->get();

        return $metrics;
   }
}

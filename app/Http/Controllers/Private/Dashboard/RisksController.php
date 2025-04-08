<?php

namespace App\Http\Controllers\Private\Dashboard;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RisksController
{
    public function __invoke(){
        $risks = $this->getRisks(true);

        return view('admin.dashboard.risks', [
            'risks' => $risks,
        ]);
    }

    public function generatePDF(){
        $risks = $this->getRisks();
        $company = session('company');

        $companyLogo = $company->logo;
        $companyName = $company->name;

        $pdf = Pdf::loadView('pdf.risks-inventory', [
            'risks' => $risks,
            'companyLogo' => $companyLogo,
            'companyName' => $companyName,
        ])->setPaper('a4', 'portrait');
        return $pdf->download('inventario_de_riscos.pdf');
    }

    private function getRisks($onlyCritical = false){
        $usersLatestCollections = User::
        whereRelation('companies', 'companies.id', session('company')->id)
        ->has('testCollections')
        ->with('testCollections', function($query){
            $query->with('risks', function($q){
                $q->with('risk', function($j){
                    $j->with('controlActions');
                });
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
                $mappedRisks[$test][$riskResult->risk->name]['control-actions'] = $riskResult->risk->controlActions;
            }
        }
        
        foreach($mappedRisks as $testName => $test){
            foreach($test as $riskName => $risk){
                $average =  array_sum($risk['score']) / count($risk['score']);

                if($onlyCritical){
                    if(ceil($average) != 3){
                        unset($mappedRisks[$testName][$riskName]);
                    } else{
                        $mappedRisks[$testName][$riskName]['score'] = ceil($average);
                        $mappedRisks[$testName][$riskName]['risk'] = "Risco Alto";
                    }
                } else{   
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
        }

        return $mappedRisks;
   }
}

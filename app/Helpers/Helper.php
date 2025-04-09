<?php

namespace App\Helpers;

use App\Models\Risk;
use App\Models\TestType;
use App\Models\User;

class Helper
{
    /**
     * Retorna um array com a última coleção de testes de cada usuário.
     * @return array
     */
    public function getUsersLatestCollections(){        
        $usersLatestCollections = User::
        whereRelation('companies', 'companies.id', session('company')->id)
        ->has('collections')
        ->with('collections', function($query){
            $query->with('tests')->orderBy('created_at', 'desc')->limit(1);
        })
        ->get();
        
        return $usersLatestCollections;
    }

    public static function getTestRisks(TestType $testType){
        $risks = Risk::whereHas('questionMaps', function ($q) use ($testType) {
                $q->whereIn('question_id', $testType->questions->pluck('id'));
            })
            ->with(['questionMaps' => function ($q) use ($testType) {
                $q->whereIn('question_id', $testType->questions->pluck('id'));
            }])
            ->get();
        
        return $risks;
    }
}

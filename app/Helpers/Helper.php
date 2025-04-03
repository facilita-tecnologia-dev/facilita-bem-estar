<?php

namespace App\Helpers;
use App\Models\User;

class Helper
{
    /**
     * Retorna um array com a última coleção de testes de cada usuário.
     * @return array
     */
    public function getUsersLatestCollections(){        
        $usersLatestCollections = User::query()
        ->where('company_id', '=', session('company')->id)
        ->with('testCollections', function($query){
            $query->with('tests')->orderBy('created_at', 'desc')->limit(1);
        })
        ->get();
        
        return $usersLatestCollections;
    }
}

<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Helpers\Helper;
use App\Models\User;

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
        $generalResults = $this->getCompiledResults();
        $testsParticipation = $this->getTestsParticipation(); 

        return view('admin.dashboard.index', [
            'generalResults' => $generalResults,
            'testsParticipation' => $testsParticipation
        ]);
    }

    /**
     * Retorna um array com os dados compilados de todos os testes.
     * @return array
     */
    private function getCompiledResults(): array{
        $usersLatestCollections = $this->usersLatestCollections;
        $compiledResults = [];
        
        foreach($usersLatestCollections as $user){
            if(count($user->testCollections) > 0){
                foreach($user->testCollections[0]->tests as $test){
                    $severity = $test['severity_title'];
                    $testName = $test['test_name'];
                    
                    if(!isset($compiledResults[$testName][$severity]['count'])){
                        $compiledResults[$testName][$severity]['count'] = 0;
                    }
                    
                    $compiledResults[$testName][$severity]['severity_color'] = $test['severity_color'];
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

        
        $users = User::query()->where('company_id', '=', session('company')->id)->get()->toArray();
        
        $countUsers = count($users);
        
        $testsParticipation = [$countCollections, ($countUsers - $countCollections)];

        return $testsParticipation;
   }
}

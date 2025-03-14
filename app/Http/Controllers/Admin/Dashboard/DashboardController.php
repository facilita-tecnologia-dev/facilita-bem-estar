<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Models\PendingTestAnswer;
use App\Models\TestCollection;
use App\Models\TestForm;
use App\Models\TestType;
use App\Models\User;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    public function index(){
        $generalResults = $this->getGeneralResults();
        $testsParticipation = $this->getTestsParticipation(); 

        return view('admin.dashboard.index', [
            'generalResults' => $generalResults,
            'testsParticipation' => $testsParticipation
        ]);
    }


    private function getUsersLatestTestCollections(){
        $userRoles = DB::table('role_user')->where('role_id', '=', 2)->get();
        
        $users = User::query()->where('company_id', '=', session('company_id'))->whereIn('id', $userRoles->pluck('user_id'))->get();

        $usersLatestTestCollections = TestCollection::whereIn('user_id', $users->pluck('id'))->whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->groupBy('user_id');
        })->with('tests')->get();

        return $usersLatestTestCollections;
    }

    private function getGeneralResults(){
        $usersLatestCollections = $this->getUsersLatestTestCollections()->toArray();

        $generalResults = [];

        foreach($usersLatestCollections as $testCollection){
            foreach($testCollection['tests'] as $test){
                $severity = $test['severity_title'];
                $testName = $test['test_name'];

                if(!isset($generalResults[$testName][$severity]['count'])){
                    $generalResults[$testName][$severity]['count'] = 0;
                }

                $generalResults[$testName][$severity]['severity_color'] = $test['severity_color'];
                $generalResults[$testName][$severity]['count'] += 1;
            }
            
        }

        return $generalResults;
    }

    private function getTestsParticipation(){
        $usersLatestTestCollections = $this->getUsersLatestTestCollections();

        $testCollectionsArray = $usersLatestTestCollections->toArray();
        $countTestCollections = count($testCollectionsArray);

        
        $users = User::query()->where('company_id', '=', session('company_id'))->get();
        
        
        $usersArray = $users->toArray();
        $countUsers = count($usersArray);
        
        
        $countTestsParticipation = [$countTestCollections, ($countUsers - $countTestCollections)];


        return $countTestsParticipation;
   }
}

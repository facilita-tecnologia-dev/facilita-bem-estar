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

        $usersLatestTestCollections = TestCollection::whereIn('id', $userRoles->pluck('user_id'))->whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->groupBy('user_id');
        })->get();


        return $usersLatestTestCollections;
    }

    private function getGeneralResults(){
       $usersLatestCollections = $this->getUsersLatestTestCollections();

        $usersLatestTestResults = TestForm::query()->whereIn('test_collection_id', $usersLatestCollections->pluck('id'))->get()->groupBy('test_name')->toArray();
        
        $generalResults = [];

        foreach($usersLatestTestResults as $test){
            $ungroupedTestType = $test;
            $test = [];
            
            foreach($ungroupedTestType as $testResult){
                $severity = $testResult['severity_title'];
                
                if(!isset($test[$severity])){
                    $test[$severity] = [];
                }
                
                $test[$severity][] = $testResult;
            }
        

            $generalResults[$ungroupedTestType[0]['test_name']] = $test;
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

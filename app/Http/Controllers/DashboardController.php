<?php

namespace App\Http\Controllers;

use App\Models\TestCollection;
use App\Models\TestForm;
use App\Models\User;
use App\Services\TestService;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    protected $testService;
    
    public function __construct(TestService $testService)
    {
        $this->testService = $testService;
    }

    public function index(){
        $generalResults = $this->getGeneralResults();

        $testsParticipation = $this->getTestsParticipation(); 
        

        return view('dashboard', [
            'generalResults' => $generalResults,
            'testsParticipation' => $testsParticipation
        ]);
    }


    private function getUsersLatestTestCollections(){
        $usersLatestTestCollections = TestCollection::whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->groupBy('user_id');
        })->get();

        return $usersLatestTestCollections;
    }


    private function getGeneralResults(){
        $usersLatestTestCollections = $this->getUsersLatestTestCollections();

        $usersLatestTestResults = TestForm::whereIn('test_collection_id', $usersLatestTestCollections->pluck('id'))->get()->groupBy('testName')->toArray();

        $generalResults = [];

        foreach($usersLatestTestResults as $item){
            $ungroupedTestType = $item;
            $item = [];

            foreach($ungroupedTestType as $testResult){
                $severity = $testResult['severityTitle'];

                if(!isset($item[$severity])){
                    $item[$severity] = [];
                }

                $item[$severity][] = $testResult;
            }


            $generalResults[$ungroupedTestType[0]['testName']] = $item;
        }

        return $generalResults;
    }

    private function getTestsParticipation(){
        $usersLatestTestCollections = $this->getUsersLatestTestCollections();

        $testCollectionsArray = $usersLatestTestCollections->toArray();
        $countTestCollections = count($testCollectionsArray);

        $users = User::all();

        $usersArray = $users->toArray();
        $countUsers = count($usersArray);

        
        $countTestsParticipation = [$countTestCollections, ($countUsers - $countTestCollections)];

        return $countTestsParticipation;
   }
}

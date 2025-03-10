<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\TestCollection;
use App\Models\TestForm;
use App\Models\TestType;
use App\Models\User;
use App\Services\TestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController
{
    public function index(){
        $generalResults = $this->getGeneralResults();
        $testsParticipation = $this->getTestsParticipation(); 

        return view('dashboard.index', [
            'generalResults' => $generalResults,
            'testsParticipation' => $testsParticipation
        ]);
    }


    // public function renderIndividualTestList(Request $request, $test){
    //     $severity = $request['severidade'];
    //     $search = $request['search'];

    //     if($severity){
    //         $testResults = $this->getIndividualTestStats($test, $severity, $search);
    //     } else{
    //         $testResults = $this->getIndividualTestStats($test);
    //     }



    //     $testStatsList = [];

    //     foreach($testResults as $key => $testCollection){

    //         if($testCollection['tests']){
    //             $userName = $testCollection["user"]["name"];
    //             $userAge = $testCollection["user"]["age"];
    //             $userOccupation = $testCollection["user"]["occupation"];
                
    //             $testTotalPoints = $testCollection["tests"][0]["total_points"];
    //             $testSeverityTitle = $testCollection["tests"][0]['severityTitle'];
    //             $testSeverityColor = $testCollection["tests"][0]['severityColor'];
    //             $testRecommendation = $testCollection["tests"][0]['recommendation'];
                
    //             $testStatsList[] = [
    //                 'name' => $userName,
    //                 'age' => $userAge,
    //                 'occupation' => $userOccupation,
    //                 'testTotalPoints' => $testTotalPoints,                
    //                 'testSeverityTitle' => $testSeverityTitle,
    //                 'testSeverityColor' => $testSeverityColor,
    //                 'testRecommendation' => $testRecommendation,
    //             ];
    //         }
    //     }


    //     return view('dashboard.individual-test-list', [
    //         'testName' => $test,
    //         'testStatsList' => $testStatsList
    //     ]);
    // }


    private function getUsersLatestTestCollections(){
        $usersLatestTestCollections = TestCollection::whereIn('created_at', function($query){
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

        $users = User::all();

        $usersArray = $users->toArray();
        $countUsers = count($usersArray);

        
        $countTestsParticipation = [$countTestCollections, ($countUsers - $countTestCollections)];

        return $countTestsParticipation;
   }
}

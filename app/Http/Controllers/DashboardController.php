<?php

namespace App\Http\Controllers;

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

    public function renderIndividualTestStats($test){
        $testResults = $this->getIndividualTestStats($test);

        $testStats = [];
        
        // Monta o array com os usuarios por severidade
        foreach ($testResults as $key => $testCollection) {
            $testSeverityName = $testCollection['tests'][0]["severityTitle"];
            $testUser = $testCollection['user']['name'];
            $testSeverityColor = $testCollection['tests'][0]["severityColor"];
            
            if(!isset($testStats[$test][$testSeverityColor])){
                $testStats[$test][$testSeverityColor] = [];
            }

            $testStats[$test][$testSeverityColor]['severityName'] = $testSeverityName;
            $testStats[$test][$testSeverityColor]['users'][] = $testUser;
        }
        
        ksort($testStats[$test]);
        
        
        return view('dashboard.individual-test-result', [
            'testStats' => $testStats
        ]);
    }

    public function renderIndividualTestList(Request $request, $test){
        $testResults = $this->getIndividualTestStats($test);

        $testStatsList = [];

        foreach($testResults as $key => $testCollection){
            $userName = $testCollection["user"]["name"];
            $userAge = $testCollection["user"]["age"];
            $userOccupation = $testCollection["user"]["occupation"];

            $testTotalPoints = $testCollection["tests"][0]["total_points"];
            $testSeverityTitle = $testCollection["tests"][0]['severityTitle'];
            $testSeverityColor = $testCollection["tests"][0]['severityColor'];
            $testRecommendation = $testCollection["tests"][0]['recommendation'];

            $testStatsList[] = [
                'name' => $userName,
                'age' => $userAge,
                'occupation' => $userOccupation,
                'testTotalPoints' => $testTotalPoints,                
                'testSeverityTitle' => $testSeverityTitle,
                'testSeverityColor' => $testSeverityColor,
                'testRecommendation' => $testRecommendation,
            ];
        }

        return view('dashboard.individual-test-list', [
            'testName' => $test,
            'testStatsList' => $testStatsList
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
        
        $usersLatestTestResults = TestForm::query()->whereIn('test_collection_id', $usersLatestTestCollections->pluck('id'))->get()->groupBy('testName')->toArray();
        
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

   private function getIndividualTestStats($test){
        $testResults = TestCollection::whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->groupBy('user_id');
        })
        ->with('user')
        ->with('tests', function($query) use($test) {
            return $query->where('testName','=', $test)->orderBy('severityColor');
        })
        ->get()
        ->toArray();


        return $testResults;
   }
}

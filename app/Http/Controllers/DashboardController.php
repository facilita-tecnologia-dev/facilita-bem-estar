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

    public function renderIndividualTestStats(Request $request, $test){
        $testStats = $this->getIndividualTestStats($test);
        
        return view('dashboard.individual-test-result', [
            'testStats' => $testStats
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

        $groupedTestResults = [];
        
        // Monta o array com os usuarios por severidade
        foreach ($testResults as $key => $testCollection) {
            $testSeverityName = $testCollection['tests'][0]["severityTitle"];
            $testUser = $testCollection['user']['name'];
            $testSeverityColor = $testCollection['tests'][0]["severityColor"];
            
            if(!isset($groupedTestResults[$test][$testSeverityColor])){
                $groupedTestResults[$test][$testSeverityColor] = [];
            }

            $groupedTestResults[$test][$testSeverityColor]['severityName'] = $testSeverityName;
            $groupedTestResults[$test][$testSeverityColor]['users'][] = $testUser;
        }
        
        ksort($groupedTestResults[$test]);

        return $groupedTestResults;
   }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\TestCollection;
use App\Models\TestForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class IndividualTestListController
{
    public function index(Request $request, $test){
        $search = $request['search'];
        $severity = $request['severidade'];

        $testResults = User::query()
            ->when($search, function($query) use($search){
                return $query->where('name', 'like', "%$search%");
            })
            ->with('testCollections.tests', function($query) use($test, $severity){
                $query->where('test_name', '=', $test)
                ->when($severity, function($query) use ($severity) {
                    return $query->where('severity_title', '=', $severity);
                });
            })
            ->get();

        
        $testStatsList = [];

        foreach($testResults as $key => $user){
            if($user && isset($user->testCollections[0]->tests[0])){
                $userId = $user->id;
                $userName = $user->name;
                $userAge = $user->age;
                $userOccupation = $user->occupation;

                
                $testTotalPoints = $user->testCollections[0]->tests[0]['total_points'];
                $testSeverityTitle = $user->testCollections[0]->tests[0]['severity_title'];
                $testSeverityColor = (int) $user->testCollections[0]->tests[0]['severity_color'];
                $testRecommendation = $user->testCollections[0]->tests[0]['recommendation'];
                
                $testStatsList[] = [
                    'userId' => $userId,
                    'name' => $userName,
                    'age' => $userAge,
                    'occupation' => $userOccupation,
                    'testTotalPoints' => $testTotalPoints,                
                    'testSeverityTitle' => $testSeverityTitle,
                    'testSeverityColor' => $testSeverityColor,
                    'testRecommendation' => $testRecommendation,
                ];
            }
        }

        $testsSorted =  $this->bubbleSortNames($testStatsList);

        $severities = $this->getSeveritiesToFilter($test);

        return view('dashboard.individual-test-list', [
            'testName' => $test,
            'testStatsList' => $testsSorted,
            'severities' => $severities
        ]);
    }

    private function bubbleSortSeverities($array) {
        $keys = array_keys($array);
        $n = count($keys);
        
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($array[$keys[$j]]['testSeverityColor'] < $array[$keys[$j + 1]]['testSeverityColor']) {
                    // Swap the elements
                    $temp = $keys[$j];
                    $keys[$j] = $keys[$j + 1];
                    $keys[$j + 1] = $temp;
                }
            }
        }
        
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $array[$key];
        }
        
        return $result;
    }

    private function bubbleSortNames($array) {
        $keys = array_keys($array);
        $n = count($keys);

        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                // Comparando em ordem crescente (alterando o sinal de '<' para '>')
                if ($array[$keys[$j]]['name'] > $array[$keys[$j + 1]]['name']) {
                    // Swap the elements
                    $temp = $keys[$j];
                    $keys[$j] = $keys[$j + 1];
                    $keys[$j + 1] = $temp;
                }
            }
        }

        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $array[$key];
        }
        
        return $result;
    }

    private function getSeveritiesToFilter($test){
        $tests = TestForm::query()->where('test_name', '=', $test)->get();

        $severities = [];

        foreach ($tests as $key => $test) {
            if(!in_array($test->severity_color, $severities)){
                $severities[$test->severity_title] = (int) $test->severity_color;
            }
        }

        arsort($severities);

        return $severities;
    }
}

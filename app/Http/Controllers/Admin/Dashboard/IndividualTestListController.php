<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Models\TestCollection;
use App\Models\TestForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class IndividualTestListController
{
    protected $users;

    public function __construct()
    {
        $this->users = User::query()->where('company_id', '=', session('company_id'))->get();
    }

    public function index(Request $request, $test){
        $search = $request['search'];
        $severity = $request['severidade'];
        $gender = $request['sexo'];
        $department = $request['setor'];
        

        $testResults = User::query()
            ->where('company_id', '=', session('company_id'))
            ->when($search, function($query) use($search){
                return $query->where('name', 'like', "%$search%");
            })
            ->when($gender, function($query) use($gender){
                return $query->where('gender', '=', $gender);
            })
            ->when($department, function($query) use($department){
                return $query->where('department', '=', $department);
            })
            ->with('testCollections.tests', function($query) use($test, $severity){
                $query->where('test_name', '=', $test)
                ->when($severity, function($query) use ($severity) {
                    return $query->where('severity_title', '=', $severity);
                });
            })
            ->get();

        // dd($testResults);
        
        $testStatsList = [];

        foreach($testResults as $key => $user){
            if($user && isset($user->testCollections[0]->tests[0])){
                $userId = $user->id;
                $userName = $user->name;
                $userAge = $user->age;
                $userGender = $user->gender;
                $userOccupation = $user->occupation;
                $userDepartment = $user->department;

                
                $testTotalPoints = $user->testCollections[0]->tests[0]['total_points'];
                $testSeverityTitle = $user->testCollections[0]->tests[0]['severity_title'];
                $testSeverityColor = (int) $user->testCollections[0]->tests[0]['severity_color'];
                $testRecommendation = $user->testCollections[0]->tests[0]['recommendation'];
                
                $testStatsList[] = [
                    'userId' => $userId,
                    'name' => $userName,
                    'age' => $userAge,
                    'gender' => $userGender,
                    'department' => $userDepartment,
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
        $genders = $this->getGendersToFilter($test);
        $departments = $this->getDepartmentsToFilter($test);

        return view('admin.dashboard.test-results-list', [
            'testName' => $test,
            'testStatsList' => $testsSorted,
            'severities' => $severities,
            'genders' => $genders,
            'departments' => $departments,
        ]);
    }

    /*
    * Função básica de Bubble Sort para ordenar com base no nome
    * @return array
    */
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


    private function getGendersToFilter($test){
        $users = $this->users;
        $genders = [];
        
        foreach ($users as $key => $user) {
            if(!in_array($user->gender, $genders)){
                $genders[] = $user->gender;
            }
        }

        return $genders;
    }

    
    private function getDepartmentsToFilter($test){
        $users = $this->users;

        $deparments = [];
        
        foreach ($users as $key => $user) {
            if(!in_array($user->department, $deparments)){
                $deparments[] = $user->department;
            }
        }

        return $deparments;
    }
}

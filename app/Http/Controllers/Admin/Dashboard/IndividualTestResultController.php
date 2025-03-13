<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Models\TestCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndividualTestResultController
{
    public function index($test){
        $testCollections = $this->getIndividualTestStats($test);
        
        $testName = $testCollections[0]->tests[0]['test_name']; 

        $testStats = [];

        foreach($testCollections as $collection){
            $severityTitle = $collection->tests[0]['severity_title'];
            $severityColor = $collection->tests[0]['severity_color'];

            if(!isset($testStats[$collection->user->department])){
                $testStats[$collection->user->department] = [];
            }

            if(!isset($testStats[$collection->user->department]['total'])){
                $testStats[$collection->user->department]['total'] = 0;
            }

            if(!isset($testStats[$collection->user->department]['severities'][$severityTitle]['count'])){
                $testStats[$collection->user->department]['severities'][$severityTitle]['count'] = 0;
            }

            if(!isset($testStats[$collection->user->department]['severities'][$severityTitle]['severity_color'])){
                $testStats[$collection->user->department]['severities'][$severityTitle]['severity_color'] = '';
            }
            
            $testStats[$collection->user->department]['total'] += 1;
            $testStats[$collection->user->department]['severities'][$severityTitle]['count'] += 1;
            $testStats[$collection->user->department]['severities'][$severityTitle]['severity_color'] = (int) $severityColor;
        };
        
        $testsSorted = array_map(function($item){
            $item['severities'] = $this->bubbleSortSeverities($item['severities']);

            return $item;
        }, $testStats);


        
        return view('admin.dashboard.individual-test-result', [
            'testName' => $testName,
            'testStats' => $testsSorted
        ]);
    }

    private function getIndividualTestStats($test){
        $userRoles = DB::table('role_user')->where('role_id', '=', 2)->get();
        $users = User::query()->where('company_id', '=', session('company_id'))->get();

        $testResults = TestCollection::whereIn('user_id', $users->pluck('id'))->whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->groupBy('user_id');
        })
        ->with('user')
        ->with('tests', function($query) use($test) {
            $query->where('test_name','=', $test)->orderBy('severity_color');
        })
        ->get();


        return $testResults;
    }

    private function bubbleSortSeverities($array) {
        $keys = array_keys($array);
        $n = count($keys);
        
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($array[$keys[$j]]['severity_color'] < $array[$keys[$j + 1]]['severity_color']) {
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
}

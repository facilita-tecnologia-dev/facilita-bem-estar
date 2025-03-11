<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\TestCollection;
use Illuminate\Http\Request;

class IndividualTestResultController
{
    public function index($test){
        $testCollections = $this->getIndividualTestStats($test);
        
        $testName = $testCollections[0]->tests[0]['test_name']; 

        $testStats = [];

        foreach($testCollections as $collection){
            $severityTitle = $collection->tests[0]['severity_title'];
            $severityColor = $collection->tests[0]['severity_color'];

            if(!isset($testStats[$collection->user->occupation])){
                $testStats[$collection->user->occupation] = [];
            }

            if(!isset($testStats[$collection->user->occupation]['total'])){
                $testStats[$collection->user->occupation]['total'] = 0;
            }

            if(!isset($testStats[$collection->user->occupation]['severities'][$severityTitle]['count'])){
                $testStats[$collection->user->occupation]['severities'][$severityTitle]['count'] = 0;
            }

            if(!isset($testStats[$collection->user->occupation]['severities'][$severityTitle]['severity_color'])){
                $testStats[$collection->user->occupation]['severities'][$severityTitle]['severity_color'] = '';
            }
            
            $testStats[$collection->user->occupation]['total'] += 1;
            $testStats[$collection->user->occupation]['severities'][$severityTitle]['count'] += 1;
            $testStats[$collection->user->occupation]['severities'][$severityTitle]['severity_color'] = (int) $severityColor;
        };
        
        $testsSorted = array_map(function($item){
            $item['severities'] = $this->bubbleSortSeverities($item['severities']);

            return $item;
        }, $testStats);
        
        return view('dashboard.individual-test-result', [
            'testName' => $testName,
            'testStats' => $testsSorted
        ]);
    }

    private function getIndividualTestStats($test){
        $testResults = TestCollection::whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->groupBy('user_id');
        })
        ->with('user')
        ->with('tests', function($query) use($test) {
            $query->where('test_name','=', $test)->orderBy('severity_color');
        })
        ->get();
        // ->toArray();


        return $testResults;
    }

    private function bubbleSortSeverities($array) {
        $keys = array_keys($array);
        $n = count($keys);
        
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($array[$keys[$j]]['severity_color'] < $array[$keys[$j + 1]]['severity_color']) {
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
}

<?php

namespace App\Http\Controllers;

use App\Models\TestCollection;
use App\Models\TestForm;
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
        $testResults = collect(session()->all())
        ->filter(function ($value, $key) {
            return str_ends_with($key, '_result');
        })
        ->toArray();

     
        $usersLatestTestCollections = TestCollection::whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->groupBy('user_id');
        })->get();

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

        return view('dashboard', ['testResults' => $testResults, 'generalResults' => $generalResults]);
   }
}

<?php

namespace App\Http\Controllers;

use App\Models\TestCollection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestResultsController
{
    public function index(){
        $lastUserTestCollection = TestCollection::whereIn('created_at', function($query){
            $query->selectRaw('MAX(created_at)')
            ->from('test_collections')
            ->where('user_id', '=', Auth::user()->id);
        })->with('tests')->first()->toArray();

        $testResults = $lastUserTestCollection['tests']; 

        $userInfo = User::query()->where('id', '=', Auth::user()->id)->first();

        return view('test-results', [
            'userInfo' => $userInfo,
            'testResults' => $testResults,
        ]);
    }
}

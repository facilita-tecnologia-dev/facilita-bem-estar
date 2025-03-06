<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestResultsController
{
    public function index(){
        $testResults = collect(session()->all())
        ->filter(function ($value, $key) {
            return str_ends_with($key, '_result');
        })
        ->toArray();

        $userInfo = User::query()->where('id', '=', Auth::user()->id)->first();

        $groupedData = ['testResults' => $testResults, 'userInfo' => $userInfo->toArray()];

        return view('test-results', [
            'testResults' => $testResults,
            'groupedData' => $groupedData,
        ]);
    }
}

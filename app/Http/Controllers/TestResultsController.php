<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestResultsController
{
    public function index(){
        $testResults = collect(session()->all())
        ->filter(function ($value, $key) {
            return str_ends_with($key, '_result');
        })
        ->toArray();

        return view('test-results', [
            'testResults' => $testResults,
        ]);
    }
}

<?php

namespace App\Http\Controllers;


class DashboardController
{

   public function index(){
        $testResults = collect(session()->all())
        ->filter(function ($value, $key) {
            return str_ends_with($key, '_result');
        })
        ->toArray();

        return view('dashboard', ['testResults' => $testResults]);
   }
}

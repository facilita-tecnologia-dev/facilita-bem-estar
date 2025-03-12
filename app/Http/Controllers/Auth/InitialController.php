<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

class InitialController
{
    public function index(){
        return view('auth.initial');
    }
}

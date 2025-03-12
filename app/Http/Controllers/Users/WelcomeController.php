<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeController
{
    public function index(){
        return view('welcome');
    }

}

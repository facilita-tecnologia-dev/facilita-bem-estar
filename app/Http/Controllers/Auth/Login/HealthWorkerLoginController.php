<?php

namespace App\Http\Controllers\Auth\Login;

use Illuminate\Http\Request;

class HealthWorkerLoginController
{
    public function __invoke(){
        return view('auth.login.health-worker');
    }
}

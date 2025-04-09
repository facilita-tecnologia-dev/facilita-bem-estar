<?php

namespace App\Http\Controllers\Auth\Login;

class HealthWorkerLoginController
{
    public function __invoke()
    {
        return view('auth.login.health-worker');
    }
}

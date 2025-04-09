<?php

namespace App\Http\Controllers\Auth\Login;

class ExternalManagerLoginController
{
    public function __invoke()
    {
        return view('auth.login.external-manager');
    }
}

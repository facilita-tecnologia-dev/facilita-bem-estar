<?php

namespace App\Http\Controllers\Auth\Login;

use Illuminate\Http\Request;

class ExternalManagerLoginController
{
    public function __invoke(){
        return view('auth.login.external-manager');
    }
}

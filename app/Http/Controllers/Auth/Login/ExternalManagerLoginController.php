<?php

namespace App\Http\Controllers\Auth\Login;

use App\Http\Controllers\GeneralController;
use Illuminate\Http\Request;

class ExternalManagerLoginController
{
    public function __invoke(){
        return view('auth.login.external-manager');
    }
}

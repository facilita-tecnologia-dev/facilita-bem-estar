<?php

namespace App\Helpers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class AuthGuardHelper
{
    public static function user() : Authenticatable | bool {
        foreach(array_keys(config('auth.guards')) as $guard){
            if(Auth::guard($guard)->check()){
                return Auth::guard($guard)->user();
            }
        }
        
        return false;
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterExternalRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController
{
    public function __invoke(Request $request)
    {
        return view('auth.register.index');
    }

    public function attemptInternalRegister(Request $request){
        dd($request);
    }

    public function attemptExternalRegister(RegisterExternalRequest $request){
        dd($request->validated());

        
    }
}

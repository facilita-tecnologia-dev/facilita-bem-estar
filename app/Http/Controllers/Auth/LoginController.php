<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginExternalRequest;
use App\Http\Requests\LoginInternalRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Company;
use App\Models\User;
use App\Rules\validateCPF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController
{
    public function __invoke()
    {
        return view('auth.login.index');
    }

    public function attemptInternalLogin(LoginInternalRequest $request)
    {
        $user = User::where('cpf', $request->safe()->only('cpf'))->first();

        if(!$user){
            return back()->with('message', 'Usuário não encontrado.');
        }
        $userCompany = $user->companies()->first();

        session(['company' => $userCompany]);

        Auth::login($user);

        return to_route('choose-test');
    }

    public function attemptExternalLogin(LoginExternalRequest $request)
    {
        dd($request->validated());
        $user = User::where('cpf', $request->safe()->only('cpf'))->first();

        if(!$user){
            return back()->with('message', 'Usuário não encontrado.');
        }

        $userCompany = $user->companies()->first();

        session(['company' => $userCompany]);

        Auth::login($user);

        return to_route('choose-test');
    }
}

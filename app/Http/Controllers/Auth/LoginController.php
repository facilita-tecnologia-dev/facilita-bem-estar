<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginExternalRequest;
use App\Http\Requests\LoginInternalRequest;
use App\Models\Collection;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    public function __invoke()
    {
        return view('auth.login.index');
    }

    public function attemptInternalLogin(LoginInternalRequest $request)
    {
        $user = User::where('cpf', $request->safe()->only('cpf'))->first();

        if (! $user) {
            return back()->with('message', 'Usuário não encontrado.');
        }
        $userCompany = $user->companies()->first();

        session(['company' => $userCompany]);

        Auth::login($user);
        
        if(session('company')->id == 2){
            return to_route('test', Collection::where('key_name', 'organizational-climate')->first());
        }
        
        return to_route('test', Collection::where('key_name', 'psychosocial-risks')->first());
    }

    public function attemptExternalLogin(LoginExternalRequest $request)
    {
        $user = User::where('cpf', $request->safe()->only('cpf'))->first();

        if (! $user) {
            return back()->with('message', 'Usuário não encontrado.');
        }

        Auth::login($user);

        return to_route('choose-test');
    }
}

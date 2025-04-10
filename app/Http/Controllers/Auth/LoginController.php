<?php

namespace App\Http\Controllers\Auth\Login;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController
{
    public function __invoke()
    {
        return view('auth.login.employee');
    }

    public function attemptLogin(Request $request)
    {
        $validatedData = $request->validate([
            'cpf' => ['required', 'size:11'],
        ]);

        $user = User::where('cpf', $validatedData['cpf'])->first();

        if(!$user){
            return back()->with('message', 'Usuário não encontrado.');
        }

        $userCompany = $user->companies()->first();

        session(['company' => $userCompany]);

        Auth::login($user);

        return to_route('choose-test');
    }
}

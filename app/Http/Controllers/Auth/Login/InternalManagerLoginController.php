<?php

namespace App\Http\Controllers\Auth\Login;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InternalManagerLoginController
{
    public function __invoke()
    {
        return view('auth.login.internal-manager');
    }

    public function attemptLogin(Request $request)
    {
        $validatedData = $request->validate([
            'cpf' => ['required', 'size:11'],
        ]);

        $user = User::query()->where('cpf', '=', $validatedData['cpf'])->first();

        $userRole = '';
        if ($user) {
            $userRole = DB::table('company_users')->where('role_id', '=', 1)->where('user_id', '=', $user->id)->get();
        }

        if (! $user || ! ($userRole)) {
            return back()->with('errorMessage', 'O usuário não existe ou não é um administrador.');
        }

        $company = Company::query()->where('id', '=', $user->companies[0]->id)->first();

        session(['company' => $company]);
        session(['user' => $user]);

        Auth::login($user);

        return to_route('dashboard.charts');
    }
}

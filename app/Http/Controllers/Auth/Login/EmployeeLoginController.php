<?php

namespace App\Http\Controllers\Auth\Login;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeLoginController
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

        $user = User::query()->where('cpf', '=', $validatedData['cpf'])->first();

        $userRole = '';
        if ($user) {
            $userRole = DB::table('company_users')->where('role_id', '=', 2)->where('user_id', '=', $user->id)->get();
        }

        if (! $user || ! (count($userRole) > 0)) {
            return back()->with('errorMessage', 'O usuário não existe.');
        }

        $company = Company::query()->where('id', '=', $user->companies[0]->id)->first();

        session(['company' => $company]);
        session(['user' => $user]);

        Auth::login($user);

        return to_route('choose-test');
    }
}

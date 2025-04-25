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

    public function attemptInternalLogin(LoginInternalRequest $request, $company = null)
    {
        $user = User::where('cpf', $request->safe()->only('cpf'))->first();

        if (! $user) {
            return back()->with('message', 'Usuário não encontrado.');
        }

        Auth::login($user);

        $userCompanies = $user->companies;

        if (count($user->companies) > 1) {
            return redirect()->route('auth.login.show-companies');
        }

        $userCompany = $userCompanies->first();

        session(['company' => $userCompany]);

        if (session('company')->id == 2) {
            if ($user->hasRole('internal-manager')) {
                return redirect()->route('dashboard.organizational-climate');
            }

            return redirect()->route('test', Collection::where('key_name', 'organizational-climate')->first());
        }

        if ($user->hasRole('internal-manager')) {
            return redirect()->route('dashboard.psychosocial');
        }

        return redirect()->route('test', Collection::where('key_name', 'psychosocial-risks')->first());
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

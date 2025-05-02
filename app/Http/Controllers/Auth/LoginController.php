<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginExternalRequest;
use App\Http\Requests\LoginInternalRequest;
use App\Models\Collection;
use App\Models\User;
use App\Services\LoginRedirectService;
use Illuminate\Support\Facades\Auth;

class LoginController
{
    public function __invoke()
    {
        return view('auth.login.index');
    }

    public function attemptInternalLogin(LoginInternalRequest $request, LoginRedirectService $redirectService)
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

        return redirect()->to($redirectService->getRedirectRoute($user));
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

<?php

namespace App\Http\Controllers\Auth;

use App\Models\Collection;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class ChooseCompanyToLoginController
{
    public function __invoke()
    {
        $userCompanies = Auth::user()->companies;

        return view('auth.login.choose-company', compact('userCompanies'));
    }

    public function attemptInternalLoginWithCompany(Company $company)
    {
        session(['company' => $company]);

        if (session('company')->id == 2) {
            if (Auth::user()->hasRole('internal-manager')) {
                return redirect()->route('dashboard.organizational-climate');
            }

            return redirect()->route('responder-teste', Collection::where('key_name', 'organizational-climate')->first());
        }

        if (Auth::user()->hasRole('internal-manager')) {
            return redirect()->route('dashboard.psychosocial');
        }

        return redirect()->route('responder-teste', Collection::where('key_name', 'psychosocial-risks')->first());
    }
}

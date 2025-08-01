<?php

namespace App\Http\Controllers\Auth;

use App\Models\Collection;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Auth;

class ChooseCompanyToLoginController
{
    public function __invoke()
    {
        $userCompanies = Auth::user()->companies;

        return view('auth.login.user.choose-company', compact('userCompanies'));
    }

    public function attemptInternalLoginWithCompany(Company $company)
    {
        CompanyService::loadCompanyToSession($company);

        if (session('company')->id == 2) {
            if (Auth::user()->hasRole('manager')) {
                return redirect()->route('dashboard.organizational-climate');
            }

            return redirect()->route('responder-teste', Collection::where('key_name', 'organizational-climate')->first());
        }

        if (Auth::user()->hasRole('manager')) {
            return redirect()->route('dashboard.psychosocial');
        }

        return redirect()->route('responder-teste', Collection::where('key_name', 'psychosocial-risks')->first());
    }
}

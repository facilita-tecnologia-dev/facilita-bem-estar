<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\RegisterExternalRequest;
use App\Http\Requests\RegisterInternalUserRequest;
use App\Models\Company;
use App\Models\CompanyMetric;
use App\Models\Metric;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController
{
    public function showCompanyRegister()
    {
        return view('auth.register.company');
    }


    public function attemptCompanyRegister(RegisterCompanyRequest $request)
    {
        $company = DB::transaction(function () use ($request) {
            $company = Company::create([
                'name' => $request->validated('name'),
                'cnpj' => $request->validated('cnpj'),
                'password' => Hash::make($request->validated('password')),
            ]);

            $metrics = Metric::all();

            foreach($metrics as $metric){
                CompanyMetric::create([
                    'metric_id' => $metric->id,
                    'company_id' => $company->id,
                    'value' => 0,
                ]);
            }

            Auth::guard('company')->login($company);
            session()->regenerate();

            session(['company' => $company]);
        });
        
        return to_route('welcome.register-company');
    }
}

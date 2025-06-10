<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterCompanyRequest;
use App\Models\ActionPlan;
use App\Models\Company;
use App\Models\CompanyMetric;
use App\Models\ControlAction;
use App\Models\CustomControlAction;
use App\Models\Metric;
use App\Services\CompanyService;
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

            foreach ($metrics as $metric) {
                CompanyMetric::create([
                    'metric_id' => $metric->id,
                    'company_id' => $company->id,
                    'value' => null,
                ]);
            }

            $actionPlan = ActionPlan::create([
                'company_id' => $company->id,
                'name' => 'Plano de Ação de Riscos Psicossociais'
            ]);

            $controlActions = ControlAction::all();
            
            $controlActions->each(function($ca) use($company, $actionPlan) {
                CustomControlAction::create([
                    'company_id' => $company->id,
                    'action_plan_id' => $actionPlan->id,
                    'risk_id' => $ca->risk_id,
                    'content' => $ca->content,
                    'severity' => $ca->severity,
                ]);
            });
            
            Auth::guard('company')->login($company);
            session()->regenerate();

            CompanyService::loadCompanyToSession($company);
        });

        return to_route('welcome.company');
    }
}

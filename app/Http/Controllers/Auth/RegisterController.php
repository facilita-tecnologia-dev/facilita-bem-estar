<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\RegisterExternalRequest;
use App\Http\Requests\RegisterInternalRequest;
use App\Http\Requests\RegisterInternalUserRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController
{
    public function __invoke(Request $request)
    {
        return view('auth.register.index');
    }

    public function attemptInternalUserRegister(RegisterInternalUserRequest $request){
        
        $user = new User();
        $user->name = $request->validated('name');
        $user->cpf = $request->validated('cpf');

        session(['user' => $user]);

        return to_route('auth.register.internal.company');
    }

    public function showCompanyRegister(){
        return view('auth.register.company');
    }

    public function attemptCompanyRegister(RegisterCompanyRequest $request){
        DB::transaction(function() use($request) {
            $company = Company::create([
                'name' => $request->validated('name'),
                'cnpj' => $request->validated('cnpj')
            ]);

            $user = User::create([
                'name' => session('user')->name,
                'cpf' => session('user')->cpf,
            ]);

            DB::table('company_users')->insert([
                'role_id' => 1,
                'user_id' => $user->id,
                'company_id' => $company->id,
            ]);

            Auth::login($user);

            return to_route('dashboard.charts');
        });
    }

    public function attemptExternalRegister(RegisterExternalRequest $request){
        DB::transaction(function() use($request){
            $user = User::create([
                'name' => $request->validated('name'), 
                'cpf' => $request->validated('cpf'), 
                'password' => Hash::make($request->validated('password')), 
            ]);

            DB::table('company_users')->insert([
                'role_id' => 3,
                'user_id' => $user->id,
                'company_id' => null,
            ]);

            Auth::login($user);
        });
        
        return to_route('choose-test');
    }
}

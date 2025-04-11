<?php

namespace App\Http\Controllers\Auth\Login;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InternalManagerLoginController
{
    public function __invoke(){
        return view('auth.login.internal-manager');
    }

    public function attemptLogin(Request $request){
        $validatedData = $request->validate([
            'cpf' => ['required', 'size:11']
        ]);
        
        $user = User::query()->where('cpf', '=', $validatedData['cpf'])->first();
        
        $userRole = '';
        if($user){
            $userRole = DB::table('company_users')->where('role_id', '=', 1)->where('user_id', '=', $user->id)->get();
        }
        
        
        if(!$user || !($userRole)){
            return back()->with('errorMessage', 'O usuário não existe ou não é um administrador.');
        }
        
        
        session(['user' => $user]);

        if(count($user->companies) > 1){
            return to_route('auth.login.choose-company');
        } else{
            $company = $user->companies->first();
            session(['company' => $company]);
            Auth::login($user);
        }

        return to_route('dashboard.charts');
    }

    public function showChooseCompanyScreen(){
        return view('auth.login.choose-company');
    }

    public function loginWithChoosedCompany(Request $request){
        $company = session('user')->companies->where('id', $request->company_id)->first();
        session(['company' => $company]);
        Auth::login(session('user'));

        return to_route('dashboard.charts');
    }
}

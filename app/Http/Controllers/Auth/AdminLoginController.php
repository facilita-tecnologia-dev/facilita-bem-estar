<?php

namespace App\Http\Controllers\Auth;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminLoginController
{
    public function index(){
        return view('auth.login-admin');
    }

    public function attemptLogin(Request $request){
        $validatedData = $request->validate([
            'cpf' => ['required', 'size:11']
        ]);

        $user = User::query()->where('cpf', '=', $validatedData['cpf'])->first();
        
        
        if(!$user){
            return back()->with('errorMessage', 'O usuário não existe ou não é um administrador.');
        }

        // $userRole = DB::table('role_user')->where('role_id', '=', 1)->where('user_id', '=', $user->id)->get();

        $company = Company::query()->where('id', '=', $user->company_id)->first();

        session(['company_id' => $company->id]);
        
        Auth::login($user);

        return to_route('admin.welcome');
    }
}

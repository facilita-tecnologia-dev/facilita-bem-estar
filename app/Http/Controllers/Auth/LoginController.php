<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SessionErrorHelper;
use App\Http\Requests\LoginExternalRequest;
use App\Http\Requests\LoginInternalRequest;
use App\Models\Company;
use App\Models\User;
use App\Rules\validateCNPJ;
use App\Rules\validateCPF;
use App\Services\LoginRedirectService;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class LoginController
{
    protected $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;    
    }

    public function attemptInternalUserLogin(Request $request)
    {
        $validatedData = $request->validate([
            'cpf' => ['required', 'string', new validateCPF],
        ]);
        
        $user = User::firstWhere('cpf', $validatedData['cpf']);
        
        if (! $user) {
            SessionErrorHelper::flash('cpf', 'Usuário não cadastrado.');
            return back();
        }

        $redirectRoute = $this->loginService->attemptLogin($user, $validatedData);
  
        return redirect()->to($redirectRoute);
    }

    public function attemptCompanyLogin(Request $request)
    {
        $validatedData = $request->validate([
            'cnpj' => ['required', 'string', new validateCNPJ],
            'password' => ['required'],
        ]);

        $company = Company::firstWhere('cnpj', $validatedData['cnpj']);

        if (!$company) {
            SessionErrorHelper::flash('cnpj', 'Empresa não cadastrada.');
            return back();
        }

        $redirectRoute = $this->loginService->attemptLogin($company, $validatedData);

        return redirect()->to($redirectRoute);
    }

    public function showChooseCompany(User $user)
    {
        return view('auth.login.choose-company', compact('user'));
    }

    public function loginUserWithCompany(User $user, Company $company)
    {
        session(['company' => $company]);

        if($user->hasRole('manager')){
            return redirect()->to(route('auth.login.gestor.senha', $user));
        }

        $this->loginService->login($user);

        return redirect()->to($this->loginService->getRedirectRoute($user));
    }

    public function showPasswordForm(User $user){
        return view('auth.login.password', compact('user'));
    }

    public function checkManagerPassword(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'password' => ['required'],
        ]);

        $isTempPassword = str_starts_with($user->password, 'temp_') && strlen($user->password) == 37;
        
        if($isTempPassword){
            if($validatedData['password'] !== $user->password){
                SessionErrorHelper::flash('password', 'A senha está incorreta.');
                return back();
            }
        } else{
            if(!Hash::check($validatedData['password'], $user->password)){ 
                SessionErrorHelper::flash('password', 'A senha está incorreta.');
                return back();
            }
        }

        $this->loginService->login($user);
        
        if($isTempPassword){
            return redirect()->to(route('auth.login.redefinir-senha'));
        }

        return redirect()->to($this->loginService->getRedirectRoute($user));
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\AuthGuardHelper;
use App\Helpers\SessionErrorHelper;
use App\Http\Requests\LoginExternalRequest;
use App\Http\Requests\LoginInternalRequest;
use App\Models\Company;
use App\Models\User;
use App\Rules\validateCNPJ;
use App\Rules\validateCPF;
use App\Services\LoginRedirectService;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class LoginController
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;    
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

        $redirectRoute = $this->authService->authenticate($user, $validatedData);
  
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

        $redirectRoute = $this->authService->authenticate($company, $validatedData);

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

        $this->authService->login($user);

        return redirect()->to($this->authService->getRedirectLoginRoute($user));
    }

    public function showPasswordForm(User $user){
        return view('auth.login.password', compact('user'));
    }

    public function checkManagerPassword(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'password' => ['required'],
        ]);

        $isTempPassword = $this->authService->checkIsTemporaryPassword($user->password);
        if($isTempPassword){
            if($validatedData['password'] !== $user->password){
                SessionErrorHelper::flash('password', 'A senha está incorreta.');
                return back();
            }
        } else{
            if(!$this->authService->checkPasswordHash($validatedData['password'], $user->password)){
                return back();
            }
        }

        $this->authService->login($user);
        
        if($isTempPassword){
            return redirect()->to(route('auth.login.redefinir-senha'));
        }

        return redirect()->to($this->authService->getRedirectLoginRoute($user));
    }

    public function switchCompanyLogin(Request $request){
        if(request('company_id') === session('company_id')){
            return back();
        }
     
        /** @var User $user */
        $user = AuthGuardHelper::user();
        
        $roleInCurrentCompany = $user->roleInCompany(session('company'));

        $this->authService->logout($request);
        
        
        $company = Company::firstWhere('id', request('company_id'));
        $roleInRequestCompany = $user->roleInCompany($company);
        
        session(['company' => $company]);
        
        if($roleInCurrentCompany->name === 'employee' && $roleInRequestCompany->name === 'manager'){
            return redirect()->to(route('auth.login.gestor.senha', $user));
        } 

        $this->authService->login($user);

        return redirect()->to($this->authService->getRedirectLoginRoute($user));
    }
}

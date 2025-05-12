<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginExternalRequest;
use App\Http\Requests\LoginInternalRequest;
use App\Models\Collection;
use App\Models\Company;
use App\Models\User;
use App\Rules\validateCNPJ;
use App\Services\LoginRedirectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController
{
    public function showInternalUserLogin()
    {
        return view('auth.login.index');
    }

    public function showCompanyLogin(){
        return view('auth.login.company');
    }

    public function attemptInternalUserLogin(LoginInternalRequest $request)
    {
        $user = User::where('cpf', $request->safe()->only('cpf'))->first();

        if (! $user) {
            return back()->with('message', 'Usuário não encontrado.');
        }

        Auth::guard('user')->login($user);
        session()->regenerate();

        $userCompanies = $user->companies;

        if (count($user->companies) > 1) {
            return redirect()->route('auth.login.usuario-interno.escolher-empresa');
        }

        $userCompany = $userCompanies->first();
        
        session(['company' => $userCompany]);

        return redirect()->to(route('user.index'));
    }

    
    public function attemptCompanyLogin(Request $request){
        $validatedData = $request->validate([
            'cnpj' => ['required', 'string', new validateCNPJ],
            'password' => ['required'],
        ]);
        
        $company = Company::firstWhere('cnpj', $validatedData['cnpj']);
        
        if(!$company){
            return back()->with('message', 'Empresa não cadastrada.');
        }

        if(Hash::check($validatedData['password'], $company->password)){
            Auth::guard('company')->login($company);
            session()->regenerate();

            session(['company' => $company]);

            return redirect()->to(route('user.index'));
        }

        return back()->with('message', 'A senha está incorreta.');
    }

    // public function attemptExternalLogin(LoginExternalRequest $request)
    // {
    //     $user = User::where('cpf', $request->safe()->only('cpf'))->first();

    //     if (! $user) {
    //         return back()->with('message', 'Usuário não encontrado.');
    //     }

    //     Auth::login($user);

    //     return to_route('escolher-teste');
    // }
}

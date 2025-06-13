<?php

namespace App\Http\Controllers\Private;

use App\Helpers\SessionErrorHelper;
use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Password as FacadePassword;
use Illuminate\Support\Str;

class CompanyController
{
    public function create()
    {
        return view('auth.register.company');
    }

    public function show(string $id)
    {
        Gate::authorize('company-show');
        $company = session('company');

        return view('private.company.show', compact('company'));
    }

    public function edit(string $id)
    {
        Gate::authorize('company-edit');
        $company = session('company');

        return view('private.company.update', compact('company'));
    }

    public function update(Request $request)
    {
        Gate::authorize('company-edit');
        $company = session('company');

        $validatedData = $request->validate([
            'logo' => ['nullable'],
            'email' => ['required', 'email'],
        ]);

        if(isset($validatedData['logo'])){
            $path = $validatedData['logo']->store('images', 'public');
            $url = Storage::url($path);
            
            $company->logo = $url;
        }

        $company->email = $validatedData['email'];

        session(['company' => $company]);
        $company->save();

        return back()->with('message', 'Perfil da empresa atualizado com sucesso!');
    }

    public function destroy(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'password' => ['required']
        ]);

        if(!Hash::check($validatedData['password'], session('company')->password)){
            SessionErrorHelper::flash('password', 'Senha incorreta.');
            return back();
        }

        session('company')->delete();

        return redirect()->to(route('logout'));
    }

    public function resetCompanyPassword(Request $request, string $id)
    {
        $validatedData = $request->validate([
            "current_password" => ['required'],
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if(!Hash::check($validatedData['current_password'], session('company')->password)){
            SessionErrorHelper::flash('current_password', 'Senha incorreta.');
            return back();
        }

        if(Hash::check($validatedData['new_password'], session('company')->password)){
            SessionErrorHelper::flash('new_password', 'Essa senha já foi/está sendo utilizada.');
            return back();
        }

        session('company')->update([
            'password' => Hash::make($validatedData['new_password']),
        ]);

        return back()->with('message', 'Senha redefinida com sucesso!');
    }

    public function showForgotPassword()
    {
        return view('auth.login.company.forgot-password');
    }

    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Usa o broker de "users"
        $status = FacadePassword::broker('companies')->sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $user->sendPasswordResetNotification($token, 'company');
            }
        );

        return $status === FacadePassword::ResetLinkSent
        ? back()->with(['message' => __($status)])
        : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.login.company.reset-password', [
            'token' => $token,
            'email' => request('email')
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);
        
        $status = FacadePassword::broker('companies')->reset( // 👈 usa o broker certo
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Company $company, string $password) {
                $company->forceFill([
                    'password' => Hash::make($password)
                ]);
    
                $company->save();
    
                event(new PasswordReset($company));
            }
        );

        return $status === FacadePassword::PasswordReset
        ? to_route('auth.login.empresa')->with('message', __($status))
        : back()->withErrors(['password' => [__($status)]]);
    }

}

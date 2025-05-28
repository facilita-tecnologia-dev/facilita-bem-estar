<?php

namespace App\Http\Controllers\Private;

use App\Helpers\SessionErrorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

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
            'logo' => ['required'],
        ]);

        $path = $validatedData['logo']->store('images', 'public');
        $url = Storage::url($path);

        $company->logo = $url;

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
}

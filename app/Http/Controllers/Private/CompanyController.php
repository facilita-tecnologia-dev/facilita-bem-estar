<?php

namespace App\Http\Controllers\Private;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CompanyController
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register.company');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('company-show');
        $company = session('company');

        return view('private.company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('company-edit');
        $company = session('company');

        return view('private.company.update', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
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
}

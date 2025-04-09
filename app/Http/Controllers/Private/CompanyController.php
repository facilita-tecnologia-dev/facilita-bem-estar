<?php

namespace App\Http\Controllers\Private;

use App\Models\Company;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:4|max:255',
            'cnpj' => 'required|size:14'
        ]);

        
        return to_route('employee.create-first', $validatedData);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }
        
        $company = Company::where('id', '=', session('company')->id)->first();

        return view('private.company.company-profile', [
            'company' => $company,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }

        $company = session('company');

        return view('private.company.update-company-profile', [
            'company' => $company,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
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

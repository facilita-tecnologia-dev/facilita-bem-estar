<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UpdateCompanyProfileController
{
    protected $company;

    public function __construct()
    {
        $this->company = Company::where('id', '=', session('company')->id)->first();
    }

    public function __invoke(){
        return view('admin.update-company-profile', [
            'company' => $this->company,
        ]);
    }

    public function updateCompanyProfile(Request $request){
        $validatedData = $request->validate([
            'logo' => ['required'],
        ]);

        $path = $validatedData['logo']->store('images', 'public');
        $url = Storage::url($path);

        $this->company->logo = $url;

        session(['company' => $this->company]);
        $this->company->save();

        return back()->with('message', 'Perfil da empresa atualizado com sucesso!');
    }
}

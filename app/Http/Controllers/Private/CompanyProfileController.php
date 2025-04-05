<?php

namespace App\Http\Controllers\Private;

use App\Models\Company;
use Illuminate\Support\Facades\Gate;

class CompanyProfileController
{
    public function __invoke(){
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }
        
        $company = Company::where('id', '=', session('company')->id)->first();

        return view('admin.company-profile', [
            'company' => $company,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Private;

use App\Models\Company;

class CompanyProfileController
{
    public function __invoke(){
        $company = Company::where('id', '=', session('company')->id)->first();

        return view('admin.company-profile', [
            'company' => $company,
        ]);
    }
}

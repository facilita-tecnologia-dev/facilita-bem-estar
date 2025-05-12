<?php

namespace App\Http\Controllers\Private;

use App\Imports\UsersImport;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class ImportUsersController
{
    public function __invoke()
    {
        return view('private.users.import');
    }

    public function importUsers(Request $request, Company $company)
    {
        Excel::import(new UsersImport(), $request->file('import_employees')->store('temp'));

        return back()->with('message', 'Usuários importados com sucesso');
    }
}

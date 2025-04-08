<?php

namespace App\Http\Controllers\Private;

use App\Imports\UsersImport;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class ImportUsersController
{
    public function __invoke(){
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.import-employees');
    }

    public function importUsers(Request $request, Company $company){
        // dd($request->file('import_employees'), $company);
        Excel::import(new UsersImport($company), $request->file('import_employees')->store('temp'));
        
        return back()->with('message', 'Usuários importados com sucesso');
    }
}

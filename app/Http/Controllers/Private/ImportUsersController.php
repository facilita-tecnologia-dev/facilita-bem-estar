<?php

namespace App\Http\Controllers\Private;

use App\Imports\UsersImport;
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

    public function importUsers(Request $request){
        // dd($request->file('import_employees'));
        Excel::import(new UsersImport, $request->file('import_employees')->store('temp'));
        
        return back()->with('message', 'Usuários importados com sucesso');
    }
}

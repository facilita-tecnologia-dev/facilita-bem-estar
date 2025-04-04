<?php

namespace App\Http\Controllers\Private;

use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportUsersController
{
    public function __invoke(){
        return view('admin.import-employees');
    }

    public function importUsers(Request $request){
        // dd($request->file('import_employees'));
        Excel::import(new UsersImport, $request->file('import_employees')->store('temp'));
        
        return back()->with('message', 'Usuários importados com sucesso');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmployeesListController
{
    public function __invoke(){
        $employees = User::where('company_id', '=', session('company')->id)->get();

        return view('admin.employees-list', [
            'employees' => $employees
        ]);
    }
}

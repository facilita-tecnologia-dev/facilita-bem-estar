<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateEmployeeProfileController
{
    protected $employee;

    public function __invoke(Request $request, User $employee){
        $this->employee = User::where('id', '=', $employee->id)->first();

        return view('admin.update-employee-profile', [
            'employee' => $this->employee,
        ]);
    }

    public function updateEmployeeProfile(Request $request, User $employee){
        $validatedData = $request->validate([
            "name" => ['required', 'max:70'],
            "cpf" => ['required', 'max:70'],
            "age" => ['required', 'max:70'],
            "gender" => ['required', 'max:70'],
            "department" => ['required', 'max:70'],
            "occupation" => ['required', 'max:70'],
        ]);

        $employee->update($validatedData);


        if(Auth::user()->id == $employee->id){
            session(['user' => $employee]);
        }

        return back()->with('message', 'Perfil do colaborador atualizado com sucesso!');
    }

}

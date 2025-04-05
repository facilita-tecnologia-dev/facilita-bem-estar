<?php

namespace App\Http\Controllers\Private;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateEmployeeController
{
    public function __invoke(){
        $rolesToSelect = $this->getRolesToSelect();

        return view('admin.create-employee-profile', [
            'rolesToSelect' => $rolesToSelect,
        ]);
    }

    public function createEmployeeProfile(Request $request){
        $validatedData = $request->validate([
            "name" => "required|max:255",
            "cpf" => "required|max:255",
            "age" => "required|",
            "gender" => "required|max:255",
            "department" => "required|max:255",
            "occupation" => "required|max:255",
            "admission" => "nullable|date",
            "role" => "required"
        ]);
        
        // dd($validatedData);

        $userData = $request->only(['name', 'cpf', 'age', 'gender', 'department', 'occupation', 'admission']);
        $userRole = $request->only('role');

        $admissionDateFormated = Carbon::parse($userData['admission'])->format('d/m/Y');

        $employee = User::create([
            "name" => $userData['name'],
            "cpf" => $userData['cpf'],
            "age" => $userData['age'],
            "gender" => $userData['gender'],
            "department" => $userData['department'],
            "occupation" => $userData['occupation'],
            "admission" => $admissionDateFormated,
            "company_id" => session('company')->id,
        ]);

        DB::table('role_user')->insert([
            'role_id' => $userRole['role'],
            'user_id' => $employee->id
        ]);

        return to_route('employees-list')->with('message', 'Perfil do colaborador criado com sucesso!');
    }

    private function getRolesToSelect(){
        $roles = DB::table('roles')->orderBy('id', 'desc')->get()->toArray();
        $rolesFormatted = [];

        foreach($roles as $role){
            $rolesFormatted[] = ['option'=> $role->name, 'value' => $role->id];
        }

        return $rolesFormatted;
    }
}

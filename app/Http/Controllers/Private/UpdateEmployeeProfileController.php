<?php

namespace App\Http\Controllers\Private;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UpdateEmployeeProfileController
{
    protected $employee;

    public function __invoke(Request $request, User $employee){
        if (Gate::denies('view-manager-screens')) {
            abort(403, 'Acesso não autorizado');
        }
        
        $this->employee = User::where('id', '=', $employee->id)->first();

        $rolesToSelect = $this->getRolesToSelect();
        $currentUserRole = DB::table('role_user')->where('user_id', '=', $employee->id)->first()->role_id;
 
        // dd($rolesToSelect);

        return view('admin.update-employee-profile', [
            'employee' => $this->employee,
            'rolesToSelect' => $rolesToSelect,
            'currentUserRole' => $currentUserRole,
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
            "role" => ['required', 'max:70'],
        ]);

        $userData = $request->only(['name', 'cpf', 'age', 'gender', 'department', 'occupation']);
        $userRole = $request->only('role');
        
        DB::table('role_user')->where('user_id', '=', $employee->id)->update(['role_id' => $userRole['role']]);

        $employee->update($userData);


        if(Auth::user()->id == $employee->id){
            session(['user' => $employee]);
        }

        return back()->with('message', 'Perfil do colaborador atualizado com sucesso!');
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

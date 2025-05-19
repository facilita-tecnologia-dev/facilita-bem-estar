<?php

namespace App\Repositories;

use App\Enums\InternalUserRoleEnum;
use App\Imports\UsersImport;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ValidatedInput;
use Maatwebsite\Excel\Facades\Excel;

class UserRepository
{
    public function store(ValidatedInput $data): User
    {
        return DB::transaction(function () use ($data) {
            $userData = $data->except('role');

            $userRole = Role::where('display_name', InternalUserRoleEnum::from($data['role'])->value)->first();

            if($userRole->name == 'manager'){
                $userData['password'] = 'temp_' . bin2hex(random_bytes(16));
            }     

            $user = User::create($userData);

            $user->companies()->sync([session('company')->id => ['role_id' => $userRole->id]]);

            session(['company' => session('company')->load('users')]);

            return $user;
        });
    }

    public function import(Request $request): void
    {
        Excel::import(new UsersImport, $request->file('import_users')->store('temp'));

        session(['company' => session('company')->load('users')]);
    }

    public function update(ValidatedInput $data, User $user): User
    {

        return DB::transaction(function () use ($data, $user) {
            $userData = $data->except('role');
            
            $userRole = Role::where('display_name', InternalUserRoleEnum::from($data['role'])->value)->first();
            
            if($userRole->name == 'manager'){
                if(!$user->password){
                    $userData['password'] = 'temp_' . bin2hex(random_bytes(16));
                }
            }       
            
            $user->update($userData);

            $user->companies()->sync([session('company')->id => ['role_id' => $userRole->id]]);

            session(['company' => session('company')->load('users')]);

            return $user;
        });
    }

    public function destroy(User $user): void
    {
        $user->delete();

        session(['company' => session('company')->load('users')]);
    }
}

<?php

namespace App\Repositories;

use App\Enums\InternalUserRoleEnum;
use App\Helpers\SessionErrorHelper;
use App\Imports\UsersImport;
use App\Models\Role;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ValidatedInput;
use Maatwebsite\Excel\Facades\Excel;

class UserRepository
{
    public function store(ValidatedInput $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::firstWhere('cpf', $data['cpf']);

            if($user){
                $user->companies()->syncWithoutDetaching([session('company')->id => ['role_id' => 2]]);
            } else{
                $userData = $data->except('role');

                $userRole = Role::where('display_name', InternalUserRoleEnum::from($data['role'])->value)->first();

                if($userRole->name == 'manager'){
                    $userData['password'] = AuthService::createTempPassword();
                }     

                $user = User::create($userData);

                $user->companies()->sync([session('company')->id => ['role_id' => $userRole->id]]);
            }

            session()->flash('password-warning', true);
            session(['company' => session('company')->load('users')]);

            return $user;
        });
    }

    public function import(Request $request): mixed
    {
        $import = new UsersImport;

        $import->import($request->file('import_users')->store('temp'));
    
        if ($import->failures()->isNotEmpty()) {
            return $import->failures();
        }
        
        session(['company' => session('company')->load('users')]);
        return true;
    }

    public function update(ValidatedInput $data, User $user): User
    {
        return DB::transaction(function () use ($data, $user) {
            $userData = $data->except(['role', 'status']);
            
            $userRole = Role::where('display_name', InternalUserRoleEnum::from($data['role'])->value)->first();
            
            $user->companies()->syncWithoutDetaching([
                session('company')->id => ['status' => $data['status']]
            ]);

            if($userRole->name == 'manager'){
                if(!$user->password){
                    $userData['password'] = AuthService::createTempPassword();
                }
            }
            
            $user->update($userData);

            $user->companies()->sync([session('company')->id => ['role_id' => $userRole->id]]);

            session()->flash('password-warning', true);
            session(['company' => session('company')->load('users')]);

            return $user;
        });
    }

    public function destroy(User $user): void
    {
        // $user->delete();

        $user = DB::table('company_users')
        ->where('user_id', $user->id)
        ->where('company_id', session('company')->id)
        ->delete();

        session(['company' => session('company')->load('users')]);
    }
}

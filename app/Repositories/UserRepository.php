<?php

namespace App\Repositories;

use App\Enums\InternalUserRoleEnum;
use App\Imports\UsersImport;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ValidatedInput;
use Maatwebsite\Excel\Facades\Excel;

class UserRepository
{
    public function create(ValidatedInput $data)
    {
        return DB::transaction(function () use ($data) {
            $userData = $data->except('role');

            $userRole = Role::where('display_name', InternalUserRoleEnum::from($data['role'])->value)->first();
            
            $user = User::create($userData);

            $user->companies()->sync([session('company')->id => ['role_id' => $userRole->id]]);
        });
    }

    public function import(Request $request, Company $company)
    {
        return Excel::import(new UsersImport($company), $request->file('import_users')->store('temp'));
    }

    public function update(ValidatedInput $data, User $user)
    {
        return DB::transaction(function () use ($data, $user) {
            $userData = $data->except('role');

            $userRole = Role::where('display_name', InternalUserRoleEnum::from($data['role'])->value)->first();

            $user->update($userData);

            $user->companies()->sync([session('company')->id => ['role_id' => $userRole->id]]);
        });
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
}
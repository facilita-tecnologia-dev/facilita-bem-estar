<?php

namespace App\Http\Controllers\Private;

use App\Enums\GenderEnum;
use App\Enums\InternalUserRoleEnum;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Imports\UsersImport;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class UserController
{
    protected $users;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Auth::user());

        $this->users = User::whereRelation('companies', 'companies.id', session('company')->id);

        $queryStringName = $request->name;
        $queryStringDepartment = $request->department;
        $queryStringOccupation = $request->occupation;

        $users = User::whereRelation('companies', 'companies.id', session('company')->id)
            ->when($queryStringName, function ($query) use ($queryStringName) {
                return $query->where('name', 'like', "%$queryStringName%");
            })
            ->when($queryStringDepartment, function ($query) use ($queryStringDepartment) {
                return $query->where('department', '=', "$queryStringDepartment");
            })
            ->when($queryStringOccupation, function ($query) use ($queryStringOccupation) {
                return $query->where('occupation', '=', "$queryStringOccupation");
            })
            ->get();

        
        $departmentsToFilter = session('company')->users()->whereNotNull('department')->distinct()->pluck('department');
        $occupationsToFilter = session('company')->users()->whereNotNull('occupation')->distinct()->pluck('occupation');

        return view('private.users.index', compact(
            'users',
            'departmentsToFilter',
            'occupationsToFilter',
            'queryStringName',
            'queryStringDepartment',
            'queryStringOccupation',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Auth::user());

        $gendersToSelect = array_map(fn(GenderEnum $gender) => $gender->value, GenderEnum::cases());
        $rolesToSelect = array_map(fn(InternalUserRoleEnum $role) => $role->value, InternalUserRoleEnum::cases());     

        return view('private.users.create', compact('rolesToSelect', 'gendersToSelect'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        Gate::authorize('create', Auth::user());

        DB::transaction(function () use ($request) {
            $userData = $request->safe()->except('role');
            $userRole = $request->safe()->only('role');
            
            $userRoleID = $userRole['role'] === InternalUserRoleEnum::INTERNAL_MANAGER->value ? 1 : 2;
            
            $user = User::create($userData);

            $user->companies()->sync([
                session('company')->id => ['role_id' => $userRoleID]
            ]);
        });

        return to_route('user.index')->with('message', 'Perfil do colaborador criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Gate::authorize('view', Auth::user());
        
        $admission = Carbon::parse($user->admission);

        if(count($user->latestCollections)){
            $latestCollectionDate = $user->latestCollections[0]->created_at->diffForHumans();
        } else{
            $latestCollectionDate = 'Nunca';
        }

        return view('private.users.show', compact('user', 'admission', 'latestCollectionDate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('update', Auth::user());

        $gendersToSelect = array_map(fn(GenderEnum $gender) => $gender->value, GenderEnum::cases());
        $rolesToSelect = array_map(fn(InternalUserRoleEnum $role) => $role->value, InternalUserRoleEnum::cases());     

        $roleInThisCompany = $user->companies()->where('companies.id', session('company')->id)->first()->pivot->role_id;
        $userRole = $roleInThisCompany === 1 ? InternalUserRoleEnum::INTERNAL_MANAGER->value : InternalUserRoleEnum::EMPLOYEE->value;

        return view('private.users.update', compact(
            'user',
            'rolesToSelect',
            'gendersToSelect',
            'userRole',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        Gate::authorize('update', Auth::user());
        
        DB::transaction(function() use($request, $user) {
            $userData = $request->safe()->except('role');
            $userRole = $request->safe()->only('role');
            
            $userRoleID = $userRole['role'] === 'Gestor Interno' ? 1 : 2;
            
            $user->update($userData);
            
            $user->companies()->sync([
                session('company')->id => ['role_id' => $userRoleID]
            ]);

            if (Auth::user()->id == $user->id) {
                session(['user' => $user]);
                Auth::setUser($user->fresh());
            }
        });

        return back()->with('message', 'Perfil do colaborador atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        Gate::authorize('delete', Auth::user());

        $employee->delete();

        return to_route('user.index');
    }

    public function showImport()
    {
        Gate::authorize('create', Auth::user());

        return view('private.users.import');
    }

    public function import(Request $request, Company $company)
    {
        Gate::authorize('create', Auth::user());

        Excel::import(new UsersImport($company), $request->file('import_users')->store('temp'));

        return back()->with('message', 'Usuários importados com sucesso');
    }
}

<?php

namespace App\Http\Controllers\Private;

use App\Enums\GenderEnum;
use App\Enums\InternalUserRoleEnum;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Imports\UsersImport;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\User\UserElegibilityService;
use App\Services\User\UserFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class UserController
{
    protected UserFilterService $filterService;
    protected UserElegibilityService $elegibilityService;
    protected UserRepository $userRepository;

    public function __construct(UserFilterService $filterService, UserElegibilityService $elegibilityService, UserRepository $userRepository)
    {
        $this->filterService = $filterService;
        $this->elegibilityService = $elegibilityService;
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Auth::user());

        $query = Company::whereId(session('company')->id)->first()->users();

        $query = $query
            ->hasAttribute('name', 'like', "%$request->name%")
            ->hasAttribute('cpf', 'like', "%$request->cpf%")
            ->hasAttribute('gender', '=', $request->gender)
            ->hasAttribute('department', '=', $request->department)
            ->hasAttribute('occupation', '=', $request->occupation);

        $users = $query
            ->with(['latestPsychosocialCollection', 'latestOrganizationalClimateCollection'])
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->paginate(20);

        $filtersApplied = array_filter($request->query(), fn ($queryParam) => $queryParam != null);

        return view('private.users.index', compact(
            'users',
            'filtersApplied'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Auth::user());

        $gendersToSelect = array_map(fn (GenderEnum $gender) => $gender->value, GenderEnum::cases());
        $rolesToSelect = array_map(fn ($role) => 
            InternalUserRoleEnum::from($role['display_name'])->value, 
            Role::whereIn('id', [1,2])->get()->toArray()
        );

        return view('private.users.create', compact('rolesToSelect', 'gendersToSelect'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        Gate::authorize('create', Auth::user());

        $this->userRepository->create($request->safe());

        return to_route('user.index')->with('message', 'Perfil do colaborador criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Gate::authorize('view', Auth::user());

        $latestOrganizationalClimateCollectionDate = $user->latestOrganizationalClimateCollection?->created_at->diffForHumans() ?? 'Nunca';
        $latestPsychosocialCollectionDate = $user->latestPsychosocialCollection?->created_at->diffForHumans() ?? 'Nunca';

        return view('private.users.show', compact('user', 'latestPsychosocialCollectionDate', 'latestOrganizationalClimateCollectionDate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('update', Auth::user());

        $gendersToSelect = array_map(fn (GenderEnum $gender) => $gender->value, GenderEnum::cases());
        $rolesToSelect = array_map(fn ($role) => 
            InternalUserRoleEnum::from($role['display_name'])->value, 
            Role::whereIn('id', [1,2])->get()->toArray()
        );

        $roleId = $user->companies()->where('companies.id', session('company')->id)->first()->pivot->role_id;
        $roleDisplayName = Role::whereId($roleId)->first()->display_name;

        return view('private.users.update', compact(
            'user',
            'rolesToSelect',
            'gendersToSelect',
            'roleDisplayName',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        Gate::authorize('update', Auth::user());

        $this->userRepository->update($request->safe(), $user);

        return back()->with('message', 'Perfil do colaborador atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', Auth::user());

        $this->userRepository->delete($user);

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
        
        $this->userRepository->import($request, $company);

        return back()->with('message', 'Usuários importados com sucesso');
    }
}

<?php

namespace App\Http\Controllers\Private;

use App\Enums\GenderEnum;
use App\Enums\InternalUserRoleEnum;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\User\UserElegibilityService;
use App\Services\User\UserFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Auth::user());

        $query = Company::whereId(session('company')->id)->first()->users()->getQuery();
        $users = $this->filterService->sort($this->filterService->apply($query))
            ->with(['latestPsychosocialCollection', 'latestOrganizationalClimateCollection'])
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->paginate(15)->appends(request()->query());

        $filteredUserCount = $users->total();

        $filtersApplied = collect(request()->query())->except(['order_by', 'order_direction'])->filter(function($value, $key){
            return $value !== null;
        });

        return view('private.users.index', [
            'users' => $users,
            'filtersApplied' => $filtersApplied,
            'filteredUserCount' => $filteredUserCount > 0 ? $filteredUserCount : null,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Auth::user());

        $gendersToSelect = array_map(fn (GenderEnum $gender) => $gender->value, GenderEnum::cases());
        $rolesToSelect = array_map(fn ($role) => InternalUserRoleEnum::from($role['display_name'])->value,
            Role::whereIn('id', [1, 2])->get()->toArray()
        );

        return view('private.users.create', compact('rolesToSelect', 'gendersToSelect'));
    }

    public function store(UserStoreRequest $request)
    {
        Gate::authorize('create', Auth::user());
        
        $this->userRepository->create($request->safe());

        return to_route('user.index')->with('message', 'Perfil do colaborador criado com sucesso!');
    }

    public function show(User $user)
    {
        Gate::authorize('view', Auth::user());

        $latestOrganizationalClimateCollectionDate = $user['latestOrganizationalClimateCollection']?->created_at->diffForHumans() ?? 'Nunca';
        $latestPsychosocialCollectionDate = $user['latestPsychosocialCollection']?->created_at->diffForHumans() ?? 'Nunca';

        return view('private.users.show', compact('user', 'latestPsychosocialCollectionDate', 'latestOrganizationalClimateCollectionDate'));
    }

    public function edit(User $user)
    {
        Gate::authorize('update', Auth::user());

        $gendersToSelect = array_map(fn (GenderEnum $gender) => $gender->value, GenderEnum::cases());
        $rolesToSelect = array_map(fn ($role) => InternalUserRoleEnum::from($role['display_name'])->value,
            Role::whereIn('id', [1, 2])->get()->toArray()
        );

        $roleId = $user->companies()->where('companies.id', session('company')->id)->first()->pivot['role_id'];
        $roleDisplayName = Role::whereId($roleId)->first()->display_name;

        return view('private.users.update', compact(
            'user',
            'rolesToSelect',
            'gendersToSelect',
            'roleDisplayName',
        ));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        Gate::authorize('update', Auth::user());

        $this->userRepository->update($request->safe(), $user);

        return back()->with('message', 'Perfil do colaborador atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', Auth::user());

        $this->userRepository->delete($user);

        return to_route('user.index')->with('message', 'Perfil do colaborador excluído com sucesso!');
    }

    public function showImport()
    {
        Gate::authorize('create', Auth::user());

        return view('private.users.import');
    }

    public function import(Request $request)
    {
        Gate::authorize('create', Auth::user());

        $request->validate([
            'import_users' => 'required|file|mimes:xlsx|max:1024',
        ]);

        $this->userRepository->import($request);

        return back()->with('message', 'Usuários importados com sucesso');
    }
}

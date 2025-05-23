<?php

namespace App\Http\Controllers\Private;

use App\Enums\GenderEnum;
use App\Enums\InternalUserRoleEnum;
use App\Helpers\AuthGuardHelper;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Test;
use App\Models\User;
use App\Models\UserCustomPermission;
use App\Models\UserDepartmentPermission;
use App\Repositories\TestRepository;
use App\Repositories\UserRepository;
use App\Rules\validateCPF;
use App\Services\LoginService;
use App\Services\User\UserElegibilityService;
use App\Services\User\UserFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController
{
    protected UserFilterService $filterService;

    protected UserElegibilityService $elegibilityService;

    protected UserRepository $userRepository;

    protected $companyCustomTests;

    protected $defaultTests;

    public function __construct(UserFilterService $filterService, UserElegibilityService $elegibilityService, UserRepository $userRepository)
    {
        $this->filterService = $filterService;
        $this->elegibilityService = $elegibilityService;
        $this->userRepository = $userRepository;

        $this->companyCustomTests = TestRepository::companyCustomTests();
        $this->defaultTests = TestRepository::defaultTests();
    }

    public function index(Request $request)
    {
        Gate::authorize('user-index');

        $query = Company::whereId(session('company')->id)->first()->users()->getQuery();
        $users = $this->filterService->sort($this->filterService->apply($query))
            ->with(['latestPsychosocialCollection', 'latestOrganizationalClimateCollection'])
            ->select('users.id', 'users.name', 'users.birth_date', 'users.department', 'users.occupation')
            ->paginate(15)->appends(request()->query());

        $filteredUserCount = $users->total();

        $filtersApplied = collect(request()->query())->except(['order_by', 'order_direction'])->filter(function ($value, $key) {
            return $value !== null;
        });

        return view('private.users.index', [
            'users' => $users->count() ? $users : null,
            'filtersApplied' => $filtersApplied,
            'filteredUserCount' => $filteredUserCount > 0 ? $filteredUserCount : null,
            'companyCustomTests' => $this->companyCustomTests ? $this->companyCustomTests : null,
            'defaultTests' => $this->defaultTests ? $this->defaultTests : null,
        ]);
    }

    public function create()
    {
        Gate::authorize('user-create');

        $gendersToSelect = array_map(fn (GenderEnum $gender) => $gender->value, GenderEnum::cases());
        $rolesToSelect = array_map(fn ($role) => InternalUserRoleEnum::from($role['display_name'])->value,
            Role::whereIn('id', [1, 2])->get()->toArray()
        );

        return view('private.users.create', compact('rolesToSelect', 'gendersToSelect'));
    }

    public function store(UserStoreRequest $request)
    {
        Gate::authorize('user-create');

        $this->userRepository->store($request->safe());

        return to_route('user.index')->with('message', 'Perfil do colaborador criado com sucesso!');
    }

    public function show(User $user)
    {
        Gate::authorize('user-show');

        $latestOrganizationalClimateCollectionDate = $user->getCompatibleOrganizationalCollection($user->organizationalClimateCollections, $this->companyCustomTests, $this->defaultTests)?->created_at->diffForHumans() ?? 'Nunca';
        $latestPsychosocialCollectionDate = $user['latestPsychosocialCollection']?->created_at->diffForHumans() ?? 'Nunca';

        return view('private.users.show', compact('user', 'latestPsychosocialCollectionDate', 'latestOrganizationalClimateCollectionDate'));
    }

    public function edit(User $user)
    {
        Gate::authorize('user-edit');

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
        Gate::authorize('user-edit');

        $this->userRepository->update($request->safe(), $user);

        return back()->with('message', 'Perfil do colaborador atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        Gate::authorize('user-delete');

        $this->userRepository->destroy($user);

        return to_route('user.index')->with('message', 'Perfil do colaborador excluído com sucesso!');
    }

    public function showImport()
    {
        return view('private.users.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_users' => 'required|file|mimes:xlsx|max:1024',
        ]);

        $this->userRepository->import($request);

        return back()->with('message', 'Usuários importados com sucesso');
    }

    public function showPermissions(User $user)
    {
        Gate::authorize('user-permission-edit');

        $defaultPermissions = RolePermission::where('role_id', $user->roles[0]->id)
            ->with('permission')
            ->orderBy('allowed', 'desc')
            ->get();

        $customizedPermissions = UserCustomPermission::where('user_id', $user->id)
            ->with('permission')
            ->where('company_id', session('company')->id)
            ->get();

        $compiledPermissions = [];

        foreach ($defaultPermissions as $defaultPermission) {
            $customizedPermissionWithSameId = $customizedPermissions->firstWhere('permission_id', $defaultPermission->permission_id);
            if ($customizedPermissionWithSameId) {
                $compiledPermissions[$customizedPermissionWithSameId->permission->key_name] = $customizedPermissionWithSameId;
            } else {
                $compiledPermissions[$defaultPermission->permission->key_name] = $defaultPermission;
            }
        }

        usort($compiledPermissions, function ($a, $b) {
            return $b->allowed <=> $a->allowed;
        });

        return view('private.users.permissions', compact('user', 'compiledPermissions'));
    }

    public function updatePermissions(Request $request, User $user)
    {
        Gate::authorize('user-permission-edit');

        $defaultRolePermissions = RolePermission::where('role_id', $user->roles[0]->id)->with('permission')->get();
        $currentUserPermissions = UserCustomPermission::where('user_id', $user->id)->with('permission')->get();

        $newPermissions = $request->except(['_token', '_method']);

        foreach ($newPermissions as $permissionKeyName => $permissionValue) {
            $hasDefaultPermissionWithSameValue = $defaultRolePermissions
                ->where('permission.key_name', $permissionKeyName)
                ->where('allowed', $permissionValue)
                ->first();

            $hasCustomizedPermissionWithSameValue = $currentUserPermissions
                ->where('permission.key_name', $permissionKeyName)
                ->first();

            if ($hasDefaultPermissionWithSameValue) {
                if ($hasCustomizedPermissionWithSameValue) {
                    $hasCustomizedPermissionWithSameValue->delete();
                }
            } else {
                if (! $hasCustomizedPermissionWithSameValue) {
                    $permissionId = Permission::where('key_name', '=', $permissionKeyName)->value('id');

                    UserCustomPermission::create([
                        'company_id' => session('company')->id,
                        'user_id' => $user->id,
                        'permission_id' => $permissionId,
                        'allowed' => $permissionValue,
                    ]);
                }
            }
        }

        return to_route('user.show', $user)->with('message', 'Permissões atualizadas com sucesso!');
    }

    public function showDepartmentScope(User $user)
    {
        Gate::authorize('user-department-scope-edit');

        $companyDepartments = Company::firstWhere('id', session('company')->id)
            ->users()
            ->pluck('department')
            ->unique()
            ->values()
            ->sortBy(function ($department) use ($user) {
                return $department === $user->department ? 0 : 1;
            })
        ->values();

        $userDepartmentPermissions = UserDepartmentPermission::where('company_id', session('company')->id)
            ->where('user_id', $user->id)
            ->orderBy('allowed', 'desc')
            ->orderByRaw("CASE WHEN department = ? THEN 0 ELSE 1 END", [$user->department])
        ->get();


        return view('private.users.department-scope', compact('user', 'companyDepartments', 'userDepartmentPermissions'));
    }

    public function updateDepartmentScopes(Request $request, User $user)
    {
        Gate::authorize('user-department-scope-edit');

        $currentDepartmentScopes = UserDepartmentPermission::where('company_id', session('company')->id)
            ->where('user_id', $user->id)
            ->get();

        $newDepartmentScopes = $request->except(['_token', '_method']);

        if ($currentDepartmentScopes->count()) {
            foreach ($newDepartmentScopes as $departmentName => $departmentScopeValue) {
                $currentDepartment = $currentDepartmentScopes->firstWhere('department', $departmentName);
                $currentDepartment->allowed = $departmentScopeValue;
                $currentDepartment->save();
            }
        } else {
            foreach ($newDepartmentScopes as $departmentName => $departmentScopeValue) {
                UserDepartmentPermission::create([
                    'company_id' => session('company')->id,
                    'user_id' => $user->id,
                    'department' => $departmentName,
                    'allowed' => $departmentScopeValue,
                ]);
            }
        }

        return to_route('user.show', $user)->with('message', 'Visão de Setores atualizada com sucesso!');
    }

    public function resetUserPassword(Request $request)
    {
        $validatedData = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        /** @var User $user */
        $user = AuthGuardHelper::user(); 

        $user->password = Hash::make($validatedData['password']);
        $user->save();

        $loginService = app(LoginService::class);

        return redirect()->to($loginService->getRedirectRoute($user));
    }

    public function showAddExistingUser(){
        Gate::authorize('user-create');

        return view('private.users.add-existing');
    }

    public function addExistingUser(Request $request){
        Gate::authorize('user-create');
        $validatedData = $request->validate([
            'cpf' => ['required', 'string', new validateCPF],
        ]);

        $user = User::firstWhere('cpf', $validatedData['cpf']);

        if($user){
            $user->companies()->syncWithoutDetaching([session('company')->id => ['role_id' => 2]]);
        }
        
        return redirect()->to(route('user.index'));
    }
}

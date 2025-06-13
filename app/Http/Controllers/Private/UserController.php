<?php

namespace App\Http\Controllers\Private;

use App\Enums\GenderEnum;
use App\Enums\InternalUserRoleEnum;
use App\Helpers\AuthGuardHelper;
use App\Helpers\SessionErrorHelper;
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
use App\Services\AuthService;
use App\Services\User\UserElegibilityService;
use App\Services\User\UserFilterService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as FacadePassword;
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

        $user = $this->userRepository->store($request->safe());

        if($user->hasRole('manager')){
            return to_route('user.department-scope', $user)->with('message', 'Perfil do colaborador criado com sucesso!');
        }

        return to_route('user.show', $user)->with('message', 'Perfil do colaborador criado com sucesso!');
    }

    public function show(User $user)
    {
        Gate::authorize('user-show');

        $latestOrganizationalClimateCollectionDate = $user['latestPsychosocialCollection']?->created_at->diffForHumans() ?? 'Nunca';
        $latestPsychosocialCollectionDate = $user['latestPsychosocialCollection']?->created_at->diffForHumans() ?? 'Nunca';
        $hasTemporaryPassword = $user->hasTemporaryPassword();

        return view('private.users.show', compact(
            'user', 
            'latestPsychosocialCollectionDate', 
            'latestOrganizationalClimateCollectionDate', 
            'hasTemporaryPassword'
        ));
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

        return to_route('user.show', $user)->with('message', 'Perfil do colaborador atualizado com sucesso!');
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

        $importUsers = $this->userRepository->import($request);

        if($importUsers instanceof Collection){
            $importUsers = $importUsers->map(function($validationError){
                $username = $validationError->values()['nome_completo'] ?? 'Nome do colaborador ausente';
                $nameBagFormatted = str_replace('_', ' ', $validationError->errors()[0]);
                return "Linha " . $validationError->row() . " - " . $username . ' - ' . $nameBagFormatted;
            });

            return view('private.users.import', [
                'failures' => $importUsers,
            ]);
        }

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

        foreach($currentDepartmentScopes as $departmentScope){
            $newDepartmentRelated = $newDepartmentScopes[$departmentScope->department] ?? null;

            if($newDepartmentRelated){
                $departmentScope->allowed = 1;
                $departmentScope->save();
            } else{
                $departmentScope->allowed = 0;
                $departmentScope->save();
            }
        }

        foreach($newDepartmentScopes as $departmentName => $departmentScope){
            if(! $currentDepartmentScopes->firstWhere('department', $departmentName)){
                UserDepartmentPermission::create([
                    'company_id' => session('company')->id,
                    'user_id' => $user->id,
                    'department' => $departmentName,
                    'allowed' => $departmentScope,
                ]);
            }
        }


        return to_route('user.permissions', $user)->with('message', 'Visão de Setores atualizada com sucesso!');
    }

    // public function resetUserPassword(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
    //     ]);

    //     /** @var User $user */
    //     $user = AuthGuardHelper::user(); 

    //     $user->password = Hash::make($validatedData['password']);
    //     $user->save();

    //     $authService = app(AuthService::class);

    //     return redirect()->to($authService->getRedirectLoginRoute($user));
    // }

    public function resetPasswordOnShowScreen(Request $request, User $user)
    {   
        $validatedData = $request->validate([
            "current_password" => ['required'],
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        if(!Hash::check($validatedData['current_password'], $user->password)){
            SessionErrorHelper::flash('current_password', 'Senha incorreta.');
            return back();
        }

        if(Hash::check($validatedData['new_password'], $user->password)){
            SessionErrorHelper::flash('new_password', 'Essa senha já foi/está sendo utilizada.');
            return back();
        }

        $user->update([
            'password' => Hash::make($validatedData['new_password']),
        ]);

        return back()->with('message', 'Senha redefinida com sucesso!');
    }

    public function showAddExistingUser()
    {
        Gate::authorize('user-create');

        return view('private.users.add-existing');
    }

    public function addExistingUser(Request $request)
    {
        Gate::authorize('user-create');
        $validatedData = $request->validate([
            'cpf' => ['required', 'string', new validateCPF],
        ]);

        $user = User::firstWhere('cpf', $validatedData['cpf']);

        if($user){
            $user->companies()->syncWithoutDetaching([session('company')->id => ['role_id' => 2]]);
        }

        session(['company' => session('company')->load('users')]);
        
        return redirect()->to(route('user.index'));
    }

    public function checkCpf(Request $request)
    {
        $request->validate([
            'cpf' => ['required']
        ]);

        $user = User::where('cpf', $request['cpf'])->first();

        return response()->json(['user' => $user]);
    }

    public function showForgotPassword()
    {
        return view('auth.login.user.forgot-password');
    }

    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = FacadePassword::broker('users')->sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $user->sendPasswordResetNotification($token, 'user');
            }
        );

        return $status === FacadePassword::ResetLinkSent
        ? back()->with(['message' => __($status)])
        : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.login.user.reset-password', [
            'token' => $token,
            'email' => request('email')
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validatedData = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);
        
        $status = FacadePassword::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
    
                $user->save();
    
                event(new PasswordReset($user));
            }
        );

        return $status === FacadePassword::PasswordReset
        ? to_route('auth.login.usuario-interno')->with('message', __($status))
        : back()->withErrors(['password' => [__($status)]]);
    }
}

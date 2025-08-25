<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\CustomResetPassword;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [];
    protected $cachedPermissionMap;


    /* ---- Relations ---- */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_users')
            ->withPivot('role_id', 'status')
            ->withTimestamps();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_users')
        ->withPivot('company_id');
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(UserFeedback::class);
    }

    public function collections(): HasMany
    {
        return $this->hasMany(UserCollection::class, 'user_id');
    }

    public function latestPsychosocialCollection(): HasOne
    {
        return $this->hasOne(UserCollection::class)
            ->whereHas('collectionType', function ($query) {
                $query->where('collection_id', 1); // <-- ID da collectionType
            })
            ->where('company_id', session('company')->id)
            ->whereYear('created_at', now()->year)
            ->latest();
    }

    public function latestOrganizationalClimateCollection(): HasOne
    {      
        return $this->hasOne(UserCollection::class)
            ->whereHas('collectionType', function ($query) {
                $query->where('collection_id', 2); // <-- ID da collectionType
            })
            ->where('company_id', session('company')->id)
            ->whereYear('created_at', now()->year)
            ->latest();
    }

    /* ---- End Relations ---- */

    /* ---- Scopes ---- */
    public function scopeWithLatestOrganizationalClimateCollection(Builder $query, Closure $callback): Builder
    {
        return $query->with([
            'latestOrganizationalClimateCollection' => $callback,
        ]);
    }

    public function scopeWithLatestPsychosocialCollection(Builder $query, Closure $callback): Builder
    {
        return $query->with([
            'latestPsychosocialCollection' => $callback,
        ]);
    }
    /* ---- End Scopes ---- */

    /* ---- Aux/Verifiers/Conditionals ---- */
    public function roleInCompany(?Company $company = null): Role
    {
        $roles = $this->roles()->get(); 
        $company = $company ?? session('company');
        return $roles->first(fn($role) => $role->pivot->company_id == $company->id);
    }

    public function hasRole(string $role): bool
    {
        return $this->roleInCompany()->name == $role;
    }

    public function departmentScopes(): HasMany
    {
        return $this->hasMany(UserDepartmentPermission::class, 'user_id');
    }

    public function hasPermission(string $key): bool
    {
        if (!$this->cachedPermissionMap) {
            $this->cachedPermissionMap = $this->loadPermissions();
        }

        // 1. Verifica se existe permissão customizada explícita (allowed ou denied)
        if (array_key_exists($key, $this->cachedPermissionMap['custom'])) {
            return $this->cachedPermissionMap['custom'][$key] === true;
        }

        // 2. Caso contrário, verifica se está permitida via papel
        return in_array($key, $this->cachedPermissionMap['role']);
    }

    private function loadPermissions(): array
    {
        $companyId = session('company')->id;

        // 1. Permissões customizadas por usuário (tanto allowed quanto denied)
        $custom = DB::table('user_custom_permissions')
            ->join('permissions', 'permissions.id', '=', 'user_custom_permissions.permission_id')
            ->where('user_custom_permissions.user_id', $this->id)
            ->where('user_custom_permissions.company_id', $companyId)
            ->select('permissions.key_name', 'user_custom_permissions.allowed')
            ->get()
            ->mapWithKeys(fn($row) => [$row->key_name => (bool) $row->allowed])
            ->toArray();

        // 2. Papel do usuário
        $roleId = DB::table('company_users')
            ->where('user_id', $this->id)
            ->where('company_id', $companyId)
            ->value('role_id');

        // 3. Permissões por papel (somente allowed)
        $rolePermissions = [];

        if ($roleId) {
            $rolePermissions = DB::table('role_permissions')
                ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                ->where('role_permissions.role_id', $roleId)
                ->where('role_permissions.allowed', true)
                ->pluck('permissions.key_name')
                ->toArray();
        }

        return [
            'custom' => $custom,              // key => bool (true/false)
            'role'   => $rolePermissions,     // array de key_name
        ];
    }

    public function hasTemporaryPassword(): bool
    {
        return str_starts_with($this->password, 'temp_') && strlen($this->password) == 15;
    }

    /* ---- End Aux/Verifiers/Conditionals ---- */

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token, 'user'));
    }
}

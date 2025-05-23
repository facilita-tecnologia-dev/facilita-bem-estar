<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_users')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_users')
        ->withPivot('company_id')
        ->wherePivot('company_id', session('company')->id);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(UserFeedback::class);
    }

    public function collections(): HasMany
    {
        return $this->hasMany(UserCollection::class);
    }

    public function latestCollections(): HasMany
    {
        return $this->collections()->latest();
    }

    public function psychosocialRiskCollections(): HasMany
    {
        return $this->hasMany(UserCollection::class)
        ->where('collection_id', 1);
    }

    public function organizationalClimateCollections(): HasMany
    {
        return $this->hasMany(UserCollection::class)
        ->where('collection_id', 2)
        ->whereYear('created_at', now()->year);
    }

    public function latestPsychosocialCollection(): HasOne
    {
        return $this->hasOne(UserCollection::class)
            ->where('collection_id', 1)
            ->latest();
    }


    public function latestOrganizationalClimateCollection(): HasOne
    {      
        return $this->hasOne(UserCollection::class)
            ->where('collection_id', 2)
            ->latest();
    }

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

    public function scopeWithOrganizationalClimateCollections(Builder $query, Closure $callback): Builder
    {
        return $query->with([
            'organizationalClimateCollections' => $callback,
        ]);
    }

    public function scopeWithPsychosocialCollections(Builder $query, Closure $callback): Builder
    {
        return $query->with([
            'psychosocialRiskCollections' => $callback,
        ]);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    public function departmentScopes(): HasMany
    {
        return $this->hasMany(UserDepartmentPermission::class, 'user_id');
    }

    public function hasPermission(string $action): bool
    {
        $permissionId = Permission::where('key_name', $action)->value('id');

        // Permissão específica por setor
        $hasDepartmentPermission = DB::table('user_custom_permissions')
            ->where('user_id', $this->id)
            ->where('company_id', session('company')->id)
            ->where('permission_id', $permissionId)
            ->first();

        if ($hasDepartmentPermission) {
            if ($hasDepartmentPermission->allowed == true) {
                return true;
            } else {
                return false;
            }
        }

        // Verifica o papel do usuário na empresa
        $roleId = DB::table('company_users')
            ->where('user_id', $this->id)
            ->where('company_id', session('company')->id)
            ->value('role_id');

        if (! $roleId) {
            return false;
        }

        // Verifica se o papel tem permissão geral
        return DB::table('role_permissions')
            ->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->where('allowed', true)
            ->exists();
    }

    public function getCompatibleOrganizationalCollection($userOrganizationalCollectionsInThisYear, $companyCustomTests, $defaultTests): ?UserCollection
    {
        $userCollectionCompatible = $userOrganizationalCollectionsInThisYear->filter(function($userCollection) use($defaultTests, $companyCustomTests) {
            if(count($userCollection->customTests)){
                $userCollectionCompanyCustomTests = $userCollection->customTests[0]->relatedCustomTest->parentCompany->customTests;
                $customTestsAreEqual = $companyCustomTests->count() === $userCollectionCompanyCustomTests->count()
                && $companyCustomTests->every(function ($companyCustomTest) use ($userCollectionCompanyCustomTests) {
                    $relatedUserCollectionTest = $userCollectionCompanyCustomTests->firstWhere('key_name', $companyCustomTest->key_name);
                    
                    if (!$relatedUserCollectionTest) {
                        return false;
                    }

                    $companyCustomTestQuestions = $companyCustomTest->questions->pluck('id')->sort()->values()->all();
                    $relatedUserCollectionTestQuestions = $relatedUserCollectionTest->questions->pluck('id')->sort()->values()->all();

                    return $companyCustomTestQuestions === $relatedUserCollectionTestQuestions;
                });

                if($customTestsAreEqual){
                    return true;
                }
 
                $customTestsMatchDefaultTests = $userCollectionCompanyCustomTests->every(function ($customTest) use ($defaultTests) {
                    return $defaultTests->contains('id', $customTest->test_id);
                });

                if(count($companyCustomTests) < 1 && $customTestsMatchDefaultTests){
                    return true;
                }

                return false;
            }

            if(count($companyCustomTests) < 1){
                return true;
            }
        });

        return $userCollectionCompatible->first();
    }
}

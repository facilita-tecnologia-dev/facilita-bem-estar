<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'age', 'cpf', 'department', 'occupation', 'admission', 'gender'];

    /**
     * Get the companies related to this user
     *
     * @return BelongsToMany relationship with the companies
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(
            Company::class,
            'company_users',
            'user_id',
            'company_id'
        )
            ->withPivot('role_id')
            ->withTimestamps();
    }

    /**
     * Get the roles related to this user
     *
     * @return BelongsToMany relationship with the roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_users')
            ->withPivot('company_id');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    public function isManager(): bool
    {
        return $this->roles()->where('name', 'internal-manager')->exists();
    }

    /**
     * Return true if this user is an employee
     */
    public function isEmployee(): bool
    {
        return $this->roles()->where('name', 'employee')->exists();
    }

    /**
     * Returns all test collections related to this user.
     */
    public function collections(): HasMany
    {
        return $this->hasMany(UserCollection::class);
    }

    /**
     * Returns the most recent test collection related to this user.
     *
     * @return HasOne
     */
    public function latestCollections()
    {
        return $this->hasMany(UserCollection::class)
            ->whereIn('collection_id', [1, 2])
            ->limit(2)
            ->orderBy('created_at', 'desc');
    }
}

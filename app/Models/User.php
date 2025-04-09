<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    private function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'company_users')
            ->withPivot('company_id');
    }

    /**
     * Get the user role in this company
     */
    public function getRoleInThisCompany(): Role
    {
        return $this->roles()
            ->wherePivot('company_id', session('company')->id)
            ->first();
    }

    /**
     * Return true if this user is a manager
     */
    public function isManager(): bool
    {
        return $this->roles()->where('name', 'Gestor')->exists();
    }

    /**
     * Return true if this user is an employee
     */
    public function isEmployee(): bool
    {
        return $this->roles()->where('name', 'Colaborador')->exists();
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
    public function latestCollection()
    {
        return $this->hasOne(UserCollection::class)->latest();
    }
}

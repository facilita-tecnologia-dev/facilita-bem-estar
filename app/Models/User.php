<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
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

    public function latestPsychosocialCollection()
    {
        return $this->hasOne(UserCollection::class)
            ->where('collection_id', 1)
            ->latest();
        // ->latestOfMany('created_at');
    }

    // Retorna a última coleção com collection_id = 2
    public function latestOrganizationalClimateCollection()
    {
        return $this->hasOne(UserCollection::class)
            ->where('collection_id', 2)
            ->latest();
        // ->latestOfMany('created_at');
    }

    public function scopeWithLatestOrganizationalClimateCollection($query, $only = null)
    {
        $query->with([
            'latestOrganizationalClimateCollection' => function ($q) use ($only) {
                $q->withCollectionTypeName('organizational-climate')->withTests($only);
            },
        ]);
    }

    public function scopeWithLatestPsychosocialCollection($query, $only = null, $year = null)
    {
        $query->with([
            'latestPsychosocialCollection' => function ($q) use ($only, $year) {
                $q->whereBetween('created_at', [Carbon::create($year, 1, 1)->startOfDay(), Carbon::create($year, 12, 31)->endOfDay()])->withCollectionTypeName('psychosocial-risks')->withTests($only);
            },
        ]);
    }

    public function scopeHasAttribute($query, $attribute, $operator, $value){
        $query->when($value, function ($query) use ($attribute, $operator, $value) {
            $query->where($attribute, $operator, $value);
        });
    }
}

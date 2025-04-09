<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'cnpj', 'logo'];

    /**
     * Get the users related to this company
     * @return BelongsToMany
     */
    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'company_users')
                    ->withPivot('role_id')
                    ->withTimestamps();
    }

    /**
     * Get the company metrics related to this company
     * @return HasMany
     */
    public function metrics(): HasMany{
        return $this->hasMany(CompanyMetric::class, 'company_id');
    }
}

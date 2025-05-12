<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Company extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'companies';

    protected $fillable = ['name', 'cnpj', 'logo'];


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_users')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(CompanyMetric::class, 'company_id');
    }

    public function campaigns() : HasMany
    {
        return $this->hasMany(CompanyCampaign::class, 'company_id');
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [];


    public function companies()
    {
        return $this->belongsToMany(
            Company::class,        // model relacionado
            'company_users',       // tabela pivô
            'user_id',             // FK local (opcional ‑ Laravel deduz)
            'company_id'           // FK relacionada (opcional)
        )
        ->withPivot('role_id')     // coluna extra da pivô
        ->withTimestamps();        // se a tabela tiver created_at / updated_at
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'company_users')
                    ->withPivot('company_id');
    }

    public function roleInCompany($companyId)
    {
        return $this->roles()
                    ->wherePivot('company_id', $companyId)
                    ->first();  
    }

    public function isAdmin()
    {
        return $this->roles()->where('name', 'internal-manager')->exists();
    }

    public function isUser()
    {
        return $this->roles()->where('name', 'employee')->exists();
    }

    public function testCollections(){
        return $this->hasMany(Collection::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users')
                    ->withPivot('role_id')
                    ->withTimestamps();
    }
}

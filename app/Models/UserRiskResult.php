<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRiskResult extends Model
{
    /** @use HasFactory<\Database\Factories\TestTypeFactory> */
    use HasFactory;

    protected $table = 'user_risk_results';

    public function risk(){
        return $this->belongsTo(Risk::class);
    }
}

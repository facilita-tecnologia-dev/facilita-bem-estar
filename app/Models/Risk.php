<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    protected $table = 'risks';

    public function questionMaps()
    {
        return $this->hasMany(RiskQuestionMap::class, 'risk_id');
    }
}

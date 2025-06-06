<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ControlAction extends Model
{
    public function parentRisk(): BelongsTo
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }
}

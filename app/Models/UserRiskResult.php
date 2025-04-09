<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRiskResult extends Model
{
    /** @use HasFactory<\Database\Factories\TestTypeFactory> */
    use HasFactory;

    protected $table = 'user_risk_results';

    /**
     * Returns the risk to which this user risk result belongs.
     */
    public function parentRisk(): BelongsTo
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    /**
     * Returns the user collection to which this user risk result belongs.
     */
    public function parentCollection(): BelongsTo
    {
        return $this->belongsTo(UserCollection::class);
    }
}

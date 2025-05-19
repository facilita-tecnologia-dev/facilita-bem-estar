<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyCampaign extends Model
{
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $guarded = [];

    public function collection(): BelongsTo
    {
        return $this->BelongsTo(Collection::class);
    }
}

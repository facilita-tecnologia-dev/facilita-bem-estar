<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomTest extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function parentCollection(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(CustomQuestion::class);
    }
}

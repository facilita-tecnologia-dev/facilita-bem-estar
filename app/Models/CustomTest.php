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
        return $this->belongsTo(CustomCollection::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(CustomQuestion::class);
    }

    public function userTests(): HasMany
    {
        return $this->hasMany(UserTest::class, 'test_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomCollection extends Model
{
    public function tests(): HasMany
    {
        return $this->hasMany(CustomTest::class);
    }

    public function collectionType(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function userCollections(): HasMany
    {
        return $this->hasMany(UserCollection::class, 'collection_id');
    }
}

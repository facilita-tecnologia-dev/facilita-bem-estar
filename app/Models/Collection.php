<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    protected $table = 'collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'description'];

    /**
     * Returns the test collections of users that have this collection as their base.
     * @return HasMany
     */
    public function userCollections(): HasMany {
        return $this->hasMany(UserCollection::class, 'collection_id');
    }

    /**
     * Returns the tests related to this collection.
     * @return HasMany
     */
    public function tests(): HasMany {
        return $this->hasMany(Test::class, 'collection_id');
    }
}

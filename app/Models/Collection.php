<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    protected $table = 'collections';

    protected $fillable = ['name', 'description'];

    public function getRouteKeyName()
    {
        return 'key_name';
    }

    /**
     * Returns the test collections of users that have this collection as their base.
     */
    public function userCollections(): HasMany
    {
        return $this->hasMany(UserCollection::class, 'collection_id');
    }

    /**
     * Returns the tests related to this collection.
     */
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class, 'collection_id');
    }
}

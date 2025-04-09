<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCollection extends Model
{
    protected $table = 'user_collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'collection_id'];

    /**
     * Returns the user who owns this collection.
     * @return BelongsTo
     */
    public function userOwner(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Returns the base collection of this user collection.
     * @return BelongsTo
     */
    public function collectionType(): BelongsTo {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    /**
     * Returns the tests of the users related to this collection.
     * @return HasMany
     */
    public function tests(): HasMany {
        return $this->hasMany(UserTest::class, 'user_collection_id');
    }

    /**
     * Returns the risks identified for this collection based on the tests.
     * @return HasMany
     */
    public function risks(): HasMany {
        return $this->hasMany(UserRiskResult::class, 'user_collection_id');
    }
}

<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCollection extends Model
{
    use HasFactory;

    protected $table = 'user_collections';

    protected $fillable = ['user_id', 'collection_id'];

    public function userOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function collectionType(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function tests(): HasMany
    {
        return $this->hasMany(UserTest::class, 'user_collection_id');
    }

    public function customTests(): HasMany
    {
        return $this->hasMany(UserCustomTest::class, 'user_collection_id');
    }

    public function scopeWithCollectionTypeName(Builder $query, string $collection): Builder
    {
        return $query->addSelect(['collection_type_name' => Collection::select('key_name')
            ->where('key_name', $collection)
            ->take(1),
        ]);
    }

    public function scopeWithTests(Builder $query, Closure $callback): Builder
    {
        return $query->with([
            'tests' => $callback,
        ]);
    }
    
    public function scopeWithCustomTests(Builder $query, ?Closure $callback): Builder
    {
        return $query->with([
            'customTests' => $callback,
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCollection extends Model
{
    use HasFactory;

    protected $table = 'user_collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'collection_id'];

    // protected $with = ['collectionType'];

    /**
     * Returns the user who owns this collection.
     */
    public function userOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Returns the base collection of this user collection.
     */
    public function collectionType(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    /**
     * Returns the tests of the users related to this collection.
     */
    public function tests(): HasMany
    {
        return $this->hasMany(UserTest::class, 'user_collection_id');
    }

    public function scopeWithCollectionTypeName($query, $collection)
    {
        $query->addSelect(['collection_type_name' => Collection::select('key_name')
            ->where('key_name', $collection)
            ->take(1),
        ]);
    }

    public function scopeWithTests($query, $only = null)
    {
        $query->with([
            'tests' => function ($q) use ($only) {
                $q->select('id', 'user_collection_id', 'test_id')
                    ->only($only)
                    ->withTestType()
                // ->withAnswersSum()
                // ->withAnswersCount()
                    ->withAnswers();
            },
        ]);
    }
}

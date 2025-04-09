<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserTest extends Model
{
    protected $table = 'user_tests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_collection_id', 'test_id', 'score', 'severity_title', 'severity_color'];

    /**
     * Returns the parent test collection of this test.
     * @return BelongsTo
     */
    public function parentCollection(): BelongsTo {
        return $this->belongsTo(UserCollection::class, 'user_collection_id');
    }

    /**
     * Returns the base collection of this user test.
     * @return BelongsTo
     */
    public function testType(): BelongsTo {
        return $this->belongsTo(Test::class, 'test_id');
    }

    /**
     * Returns the responses for this test.
     * @return HasMany
     */
    public function answers(): HasMany {
        return $this->hasMany(UserAnswer::class, 'user_test_id');
    }

    // public function questions()
    // {
    //     return $this->hasManyThrough(
    //         TestQuestion::class,
    //         TestType::class,
    //         'id',           // Chave primária em TestType
    //         'test_id', // Chave estrangeira em TestQuestion
    //         'test_id', // Chave estrangeira em TestForm
    //         'id'            // Chave primária em TestType
    //     );
    // }
}

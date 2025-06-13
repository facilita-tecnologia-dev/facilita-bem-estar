<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Test extends Model
{
    protected $table = 'tests';

    protected $fillable = ['key_name', 'display_name', 'statement', 'reference', 'number_of_questions', 'handler_type', 'order'];

    // protected $with = ['questions', 'risks'];

    /**
     * Returns the test collection to which this test belongs.
     */
    public function parentCollection(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function userTests()
    {
        return $this->hasMany(UserTest::class, 'test_id');
    }    

    public function customTest(): HasOne
    {
        return $this->hasOne(CustomTest::class)->where('company_id', session('company')->id);
    }

    /**
     * Returns the questions related to this test.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'test_id');
    }

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }

    public function scopeWithRisks(Builder $query, Closure $callback): Builder
    {
        return $query->with([
            'risks' => $callback,
        ]);
    }

    public function scopeWithUserTests(Builder $query, Closure $callback): Builder
    {
        return $query->with([
            'userTests' => $callback,
        ]);
    }
}

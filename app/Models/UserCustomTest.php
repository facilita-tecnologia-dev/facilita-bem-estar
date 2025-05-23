<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCustomTest extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function parentUserCollection(): BelongsTo
    {
        return $this->belongsTo(UserCollection::class);
    }

    public function relatedCustomTest(): BelongsTo
    {
        return $this->belongsTo(CustomTest::class, 'custom_test_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UserCustomAnswer::class);
    }

    public function scopeWithAnswers(Builder $query)
    {
        return $query->with([
            'answers' => function ($q) {
                $q->withRelatedOptionValue();
            },
        ]);
    }

    public function scopeWithCustomTestType(Builder $query)
    {
        return $query->with('relatedCustomTest');
    }

    public function scopeJustOneCustomTest(Builder $query, string $customTestName): Builder
    {
        return $query->whereHas('relatedCustomTest', function ($subQuery) use ($customTestName) {
            $subQuery->where('display_name', $customTestName);
        });
    }
}

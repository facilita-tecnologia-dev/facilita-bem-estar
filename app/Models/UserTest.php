<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class UserTest extends Model
{
    use HasFactory;

    protected $table = 'user_tests';

    protected $fillable = ['user_collection_id', 'test_id', 'score', 'severity_title', 'severity_color'];

    // protected $with = ['testType', 'answers'];

    /**
     * Returns the parent test collection of this test.
     */
    public function parentCollection(): BelongsTo
    {
        return $this->belongsTo(UserCollection::class, 'user_collection_id');
    }

    /**
     * Returns the base collection of this user test.
     */
    public function testType(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    /**
     * Returns the responses for this test.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class, 'user_test_id');
    }

    public function scopeWithTestType(Builder $query, Closure $callback) : Builder
    {
        return $query->with([
            'testType' => function ($query) use ($callback) {
                $query->select('id', 'key_name', 'display_name', 'handler_type');

                if ($callback) {
                    $callback($query);
                }
            },
        ]);
    }

    public function scopeWithAnswers(Builder $query) : Builder
    {
        return $query->with([
            'answers' => function ($q) {
                $q->select('id', 'user_test_id', 'question_id', 'question_option_id')
                    ->withRelatedOptionValue();
            },
        ]);
    }

    public function scopeWithAnswersSum(Builder $query) : Builder
    {
        return $query->addSelect([
            'answers_sum' => DB::table('user_answers')
                ->join('question_options', 'user_answers.question_option_id', '=', 'question_options.id')
                ->selectRaw('SUM(question_options.value)')
                ->whereColumn('user_answers.user_test_id', 'user_tests.id'),
        ]);
    }

    public function scopeWithAnswersCount(Builder $query) : Builder
    {
        return $query->addSelect([
            'answers_count' => DB::table('user_answers')
                ->selectRaw('COUNT(*)')
                ->whereColumn('user_answers.user_test_id', 'user_tests.id'),
        ]);
    }

    public function scopeJustOneTest(Builder $query, string $testName) : Builder
    {
        return $query->whereHas('testType', function ($subQuery) use ($testName) {
            $subQuery->where('display_name', $testName);
        });
    }
}

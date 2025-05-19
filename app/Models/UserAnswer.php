<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    use HasFactory;

    protected $table = 'user_answers';

    // protected $with = ['relatedOption'];

    protected $fillable = ['user_test_id', 'question_id', 'question_option_id'];

    /**
     * Returns the parent user test of this answer.
     */
    public function parentTest(): BelongsTo
    {
        return $this->belongsTo(UserTest::class, 'user_test_id');
    }

    /**
     * Returns the parent question this answer.
     */
    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Returns the option related this answer.
     */
    public function relatedOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'question_option_id');
    }

    public function scopeWithRelatedOptionValue(Builder $query): Builder
    {
        return $query->addSelect(['related_option_value' => Option::select('value')
            ->whereColumn('id', 'user_answers.question_option_id')
            ->take(1),
        ]);
    }

    // public function scopeWithParentQuestionId($query)
    // {
    //     $query->addSelect(['parent_question_id' => Question::select('id')
    //         ->whereColumn('id', 'user_answers.question_option_id')
    //         ->take(1),
    //     ]);
    // }
}

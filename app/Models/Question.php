<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $table = 'questions';

    protected $with = ['options'];

    protected $fillable = ['test_id', 'statement'];

    /**
     * Returns the test to which this question belongs.
     */
    public function parentTest(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    /**
     * Returns the answer options related to this question.
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class, 'question_id', 'id');
    }

    /**
     * Returns all responses from all users related to this question.
     */
    public function userAnswers(): HasMany
    {
        return $this->hasMany(UserAnswer::class, 'question_id');
    }
}

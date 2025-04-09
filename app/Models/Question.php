<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $table = 'questions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['test_id', 'statement'];

    /**
     * Returns the test to which this question belongs.
     * @return BelongsTo
     */
    public function parentTest(): BelongsTo {
        return $this->belongsTo(Test::class, 'test_id');
    }

    /**
     * Returns the answer options related to this question.
     * @return HasMany
     */
    public function options(): HasMany {
        return $this->hasMany(Option::class, 'question_id', 'id');
    }

    /**
     * Returns all responses from all users related to this question.
     * @return HasMany
     */
    public function userAnswers(): HasMany {
        return $this->hasMany(UserAnswer::class, 'question_id');
    }
}

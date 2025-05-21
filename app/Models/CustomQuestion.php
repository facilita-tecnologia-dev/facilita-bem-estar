<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomQuestion extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    protected $with = ['options'];

    public function parentTest(): BelongsTo
    {
        return $this->belongsTo(CustomTest::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(CustomQuestionOption::class);
    }

    public function relatedQuestion(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    public function userAnswers(): HasMany
    {
        return $this->hasMany(UserCustomAnswer::class, 'custom_question_id');
    }
}

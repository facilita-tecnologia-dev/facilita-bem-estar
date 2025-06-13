<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomQuestion extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function parentTest(): BelongsTo
    {
        return $this->belongsTo(CustomTest::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(CustomQuestionOption::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class, 'question_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    protected $table = 'question_options';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['question_id', 'content', 'value'];

    /**
     * Returns the question to which this option belongs.
     * @return BelongsTo
     */
    public function parentQuestion(): BelongsTo {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Returns all responses from all users related to this answer.
     * @return HasMany
     */
    public function userAnswers(): HasMany {
        return $this->hasMany(UserAnswer::class, 'question_option_id');
    }
}

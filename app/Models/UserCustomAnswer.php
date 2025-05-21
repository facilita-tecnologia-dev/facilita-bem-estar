<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCustomAnswer extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function parentCustomTest(): BelongsTo
    {
        return $this->belongsTo(UserCustomTest::class);
    }

    public function relatedQuestion(): BelongsTo
    {
        return $this->belongsTo(CustomQuestion::class);
    }

    public function relatedOption(): BelongsTo
    {
        return $this->belongsTo(CustomQuestionOption::class);
    }

    public function scopeWithRelatedOptionValue(Builder $query): Builder
    {
        return $query->addSelect(['related_option_value' => CustomQuestionOption::select('value')
            ->whereColumn('id', 'user_custom_answers.custom_question_option_id')
            ->take(1),
        ]);
    }
}

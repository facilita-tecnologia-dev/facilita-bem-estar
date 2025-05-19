<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class RiskQuestionMap extends Model
{
    protected $table = 'risk_question_map';

    /**
     * Returns the risk to which this user risk result belongs.
     */
    public function parentRisk(): BelongsTo
    {
        return $this->belongsTo(Risk::class, 'risk_id');
    }

    /**
     * Returns the question to which this risk question map belongs.
     */
    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_Id', 'id');
    }

    public function scopeWithParentQuestionStatement(Builder $query): Builder
    {
        return $query->addSelect([
            'parent_question_statement' => DB::table('questions')
                ->whereColumn('questions.id', 'risk_question_map.question_Id')
                ->select('questions.statement')
                ->limit(1),
        ]);
    }

    public function scopeWithRelatedQuestionAnswer(Builder $query): Builder
    {
        return $query->addSelect([
            'related_question_answer' => DB::table('user_answers')
                ->join('question_options', 'user_answers.question_option_id', '=', 'question_options.id')
                ->whereColumn('user_answers.question_id', 'risk_question_map.question_id')
                ->select('question_options.value')
                ->limit(1),
        ]);
    }
}

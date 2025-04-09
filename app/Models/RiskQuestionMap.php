<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskQuestionMap extends Model
{
    protected $table = 'risk_question_map';

    /**
     * Returns the risk to which this user risk result belongs.
     */
    public function parentRisk(): BelongsTo
    {
        return $this->belongsTo(Risk::class);
    }

    /**
     * Returns the question to which this risk question map belongs.
     */
    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_Id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Risk extends Model
{
    protected $table = 'risks';

    // protected $with = ['relatedQuestions:id,risk_id,question_Id', 'controlActions:id,risk_id,content'];

    /**
     * Returns the questions related to this risk.
     *
     * @return HasMany
     */
    public function relatedQuestions()
    {
        return $this->hasMany(RiskQuestionMap::class, 'risk_id');
    }

    /**
     * Returns the control actions related to this risk.
     */
    public function controlActions(): HasMany
    {
        return $this->hasMany(ControlAction::class);
    }

    public function customControlActions(): HasMany
    {
        return $this->hasMany(CustomControlAction::class);
    }

    /**
     * Returns the test related to this risk.
     */
    public function relatedTest(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    public function scopeWithRelatedQuestions(Builder $query): Builder
    {
        return $query->with('relatedQuestions', fn ($query) => $query
            ->withParentQuestionStatement()
            ->withParentQuestionInverted());
    }

    public function scopeWithControlActions(Builder $query): Builder
    {
        return $query->with('controlActions');
    }

    public function scopeWithCustomControlActions(Builder $query): Builder
    {
        return $query->with('customControlActions');
    }
}

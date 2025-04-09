<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Risk extends Model
{
    protected $table = 'risks';

    /**
     * Returns the questions related to this risk.
     * @return HasMany
     */
    public function relatedQuestions()
    {
        return $this->hasMany(RiskQuestionMap::class, 'risk_id');
    }

    /**
     * Returns the control actions related to this risk.
     * @return HasMany
     */
    public function controlActions(): HasMany {
        return $this->hasMany(ControlAction::class);
    }
}

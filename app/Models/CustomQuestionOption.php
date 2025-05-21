<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomQuestionOption extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(CustomQuestion::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    protected $table = 'tests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['key_name', 'display_name', 'statement', 'reference', 'number_of_questions', 'handler_type', 'order'];

    // protected $with = ['questions', 'risks'];

    /**
     * Returns the test collection to which this test belongs.
     */
    public function parentCollection(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    /**
     * Returns the questions related to this test.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'test_id');
    }

    public function risks()
    {
        return $this->hasMany(Risk::class);
    }

    public function scopeWithQuestions($query)
    {
        $query->with('questions:id,test_id,statement');
    }

    public function scopeWithRisks($query)
    {
        $query->with('risks', fn($query) => 
            $query->withRelatedQuestions()
                    ->withControlActions());
    }
}

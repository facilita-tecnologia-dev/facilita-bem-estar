<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    protected $table = 'user_answers';

    protected $with = ['relatedOption'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_test_id', 'question_id', 'question_option_id'];

    /**
     * Returns the parent user test of this answer.
     */
    public function parentTest(): BelongsTo
    {
        return $this->belongsTo(UserTest::class, 'user_test_id');
    }

    /**
     * Returns the parent question this answer.
     */
    public function parentQuestion(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Returns the option related this answer.
     */
    public function relatedOption(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'question_option_id');
    }
}

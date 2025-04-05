<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    /** @use HasFactory<\Database\Factories\TestQuestionFactory> */
    use HasFactory;


    public function questionOptions(){
        return $this->hasMany(QuestionOption::class, 'question_id', 'id');
    }

    public function testType(){
        return $this->belongsTo(TestType::class, 'test_type_id');
    }

    public function testAnswers()
    {
        return $this->hasMany(TestAnswer::class, 'test_question_id', 'id');
    }
}

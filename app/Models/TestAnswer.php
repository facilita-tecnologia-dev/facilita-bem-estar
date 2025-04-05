<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    public function testQuestion()
    {
        return $this->belongsTo(TestQuestion::class, 'test_question_id', 'id');
    }

    public function testForm()
    {
        return $this->belongsTo(TestForm::class, 'test_form_id', 'id');
    }
}

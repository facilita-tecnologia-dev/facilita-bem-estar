<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskQuestionMap extends Model
{
    protected $table = 'risk_question_map';

    public function question(){
        return $this->belongsTo(TestQuestion::class, 'question_Id', 'id');
    }
}

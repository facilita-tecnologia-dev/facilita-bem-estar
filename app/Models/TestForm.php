<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestForm extends Model
{
    /** @use HasFactory<\Database\Factories\TestFormFactory> */
    use HasFactory;

    public function testCollection(){
        return $this->belongsTo(TestCollection::class, 'test_collection_id');
    }

    public function testType(){
        return $this->belongsTo(TestType::class, 'test_type_id');
    }
}

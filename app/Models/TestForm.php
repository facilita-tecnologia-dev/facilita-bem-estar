<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestForm extends Model
{
    protected $table = 'user_tests';

    /** @use HasFactory<\Database\Factories\TestFormFactory> */
    use HasFactory;

    public function testCollection(){
        return $this->belongsTo(Collection::class, 'test_collection_id');
    }

    public function testType(){
        return $this->belongsTo(TestType::class, 'test_id');
    }

    public function answers(){
        return $this->hasMany(Answer::class, 'user_test_id');
    }

    public function questions()
    {
        return $this->hasManyThrough(
            TestQuestion::class,
            TestType::class,
            'id',           // Chave primária em TestType
            'test_id', // Chave estrangeira em TestQuestion
            'test_id', // Chave estrangeira em TestForm
            'id'            // Chave primária em TestType
        );
    }
}

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

    public function answers()
    {
        return $this->hasMany(TestAnswer::class, 'test_form_id', 'id');
    }

    public function questions()
    {
        return $this->hasManyThrough(
            TestQuestion::class,
            TestType::class,
            'id',           // Chave primária em TestType
            'test_type_id', // Chave estrangeira em TestQuestion
            'test_type_id', // Chave estrangeira em TestForm
            'id'            // Chave primária em TestType
        );
    }
}

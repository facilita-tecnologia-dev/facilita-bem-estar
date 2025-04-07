<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{
    /** @use HasFactory<\Database\Factories\TestTypeFactory> */
    use HasFactory;

    protected $table = 'tests';

    // public function tests(){
    //     return $this->hasMany(TestForm::class, 'test_type_id');
    // }

    public function questions(){
        return $this->hasMany(TestQuestion::class, 'test_id');
    }
}

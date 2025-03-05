<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCollection extends Model
{
    /** @use HasFactory<\Database\Factories\TestCollectionFactory> */
    use HasFactory;

    public function tests(){
        return $this->hasMany(TestForm::class, 'test_collection_id');
    }
}

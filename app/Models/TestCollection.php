<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestCollection extends Model
{
    /** @use HasFactory<\Database\Factories\TestCollectionFactory> */
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tests(){
        return $this->hasMany(TestForm::class, 'test_collection_id');
    }
}

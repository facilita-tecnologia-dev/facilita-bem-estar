<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    /** @use HasFactory<\Database\Factories\TestCollectionFactory> */
    use HasFactory;

    protected $table = 'user_collections';

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tests(){
        return $this->hasMany(TestForm::class, 'user_collection_id');
    }

    public function risks(){
        return $this->hasMany(UserRiskResult::class, 'user_collection_id');
    }
}

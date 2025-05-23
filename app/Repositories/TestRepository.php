<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\Test;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class TestRepository
{
    public static function defaultTests(){
        return Test::where('collection_id', 2)->get();
    }

    public static function companyCustomTests(){
        return Company::firstWhere('id', session('company')->id)->customTests;
    }
}

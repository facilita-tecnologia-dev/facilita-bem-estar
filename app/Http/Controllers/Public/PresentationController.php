<?php

namespace App\Http\Controllers\Public;

use Illuminate\Http\Request;

class PresentationController
{
    public function index(){
        return view('presentation');
    }
}

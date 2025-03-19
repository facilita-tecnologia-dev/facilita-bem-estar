<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresentationController
{
    public function index(){
        return view('presentation');
    }
}

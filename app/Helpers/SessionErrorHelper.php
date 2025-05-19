<?php

namespace App\Helpers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class SessionErrorHelper
{
    public static function flash(string $field, string $message): void
    {
        $errors = session()->get('errors', new ViewErrorBag());
    
        $messageBag = $errors->getBag('default') ?? new MessageBag();
        $messageBag->add($field, $message);
        
        $errors->put('default', $messageBag);
    
        session()->flash('errors', $errors); 
    }
}

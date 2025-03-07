<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        if (!Auth::check()) {
            $user = User::find(5);
            Auth::login($user);
        }


        // Auth::logout();

        // $request->session()->invalidate();
    
        // $request->session()->regenerateToken();
        

        return $next($request);
    }
}

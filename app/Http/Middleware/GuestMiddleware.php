<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();
        
        if($user){
            $userRole = DB::table('role_user')->where('user_id', '=', $user->id)->first();

            if ($userRole->role_id == 1) {
                return to_route('general-results.dashboard');
            }
    
            if ($userRole->role_id == 2) {
                return to_route('welcome');
            }
        }


        return $next($request);
    }
}

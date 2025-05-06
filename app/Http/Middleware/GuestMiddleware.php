<?php

namespace App\Http\Middleware;

use App\Models\Collection;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $user = Auth::user();

        if (Auth::check() && session('company')) {
            $userRole = $user->roles()->first();
            if ($userRole && $userRole['id'] == 1) {
                if (session('company')->id !== 2) {
                    return to_route('dashboard.psychosocial');
                }

                return to_route('dashboard.organizational-climate');
            }

            if ($userRole && $userRole['id'] == 2) {
                if (session('company')->id !== 2) {
                    return to_route('responder-teste', Collection::where('key_name', 'psychosocial-risks')->first());
                }

                return to_route('responder-teste', Collection::where('key_name', 'organizational-climate')->first());
            }
        }

        return $next($request);
    }
}

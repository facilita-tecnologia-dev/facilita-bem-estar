<?php

namespace App\Http\Middleware;

use App\Helpers\AuthGuardHelper;
use Closure;
use Illuminate\Http\Request;
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
        if (AuthGuardHelper::user()) {
            return back();
        }

        return $next($request);
    }
}

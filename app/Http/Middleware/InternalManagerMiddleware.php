<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InternalManagerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::user()->hasRole('manager')) {
            abort(403, 'Você não tem permissão para acessar essa página.');
        }

        return $next($request);
    }
}

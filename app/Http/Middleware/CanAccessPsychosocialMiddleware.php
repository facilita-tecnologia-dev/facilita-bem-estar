<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CanAccessPsychosocialMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // if(Gate::denies('access-psychosocial')){
        //     abort(403, 'Você não tem permissão para acessar essa página.');
        // }

        return $next($request);
    }
}

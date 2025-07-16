<?php

namespace App\Http\Middleware;

use App\Helpers\AuthGuardHelper;
use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (AuthGuardHelper::user()) {
            $redirect = $this->authService->getRedirectLoginRoute(AuthGuardHelper::user());

            return redirect()->to($redirect);
        }

        return $next($request);
    }
}

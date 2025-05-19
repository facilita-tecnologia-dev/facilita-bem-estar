<?php

use App\Http\Middleware\CanAccessOrganizationalMiddleware;
use App\Http\Middleware\CanAccessPsychosocialMiddleware;
use App\Http\Middleware\InternalManagerMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // 'can-access-psychosocial' => CanAccessPsychosocialMiddleware::class,
            // 'can-access-organizational' => CanAccessOrganizationalMiddleware::class,
            // 'is-manager' => InternalManagerMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // API
        $middleware->api([
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            SubstituteBindings::class,
        ]);
    })->booting(function () {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(3)
                ->response(function (Request $request, array $headers) {
                    return response('This will be handy for Rate limiters on API routes', 429, $headers);
                });
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

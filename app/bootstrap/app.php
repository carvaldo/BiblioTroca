<?php

use App\Http\Middlewares\PlainTextDefaultContentType;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
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
        // EnsureFrontendRequestsAreStateful Mantém autenticação web via sessão em caso de aplicação web. Entretanto,
        // o restante da aplicação deve ser tratado como stateless, afim de manter compatibilidade com demais clientes.
        // API
        $middleware->api([
            EnsureFrontendRequestsAreStateful::class,
            SubstituteBindings::class,
            PlainTextDefaultContentType::class
        ])
            ->convertEmptyStringsToNull()
            ->throttleApi();
    })->booting(function () {
        // Limita quantidade de requisições por minuto por usuário ou ip
        RateLimiter::for('api', function (Request $request) {
            $limit = Limit::perMinute(60);
            return $request->user() ? $limit->by($request->user()->id) : $limit->by($request->ip());
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
    })->create();

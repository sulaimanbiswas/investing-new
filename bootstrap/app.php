<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            App\Http\Middleware\SetFrontendLocale::class,
        ]);

        // Ensure the 'auth' alias uses our custom Authenticate middleware for proper admin redirects
        $middleware->alias([
            'auth' => App\Http\Middleware\Authenticate::class,
            'check.banned' => App\Http\Middleware\CheckIfBanned::class,
            'super.admin' => App\Http\Middleware\EnsureSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

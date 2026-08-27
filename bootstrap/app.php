<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function ()
        {
            Route::middleware(['web', 'auth'])                ->group(base_path('routes/auth.php'));
            Route::middleware(['web', 'auth', 'can:is-admin'])->group(base_path('routes/admin.php'));
            Route::middleware(['web', 'guest'])               ->group(base_path('routes/guest.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(
            append: [
                \Illuminate\Session\Middleware\AuthenticateSession::class,
                \App\Http\Middleware\isNeedAdminAccount::class,
                \App\Http\Middleware\isPasswordNeedChange::class,
            ],
            prepend: [
                \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
                \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            ],
        );

        $middleware->prepend(\App\Http\Middleware\indexMiddleware::class);

        $middleware->redirectTo(

            guests: fn() => route('login_get'),
            users:  fn() => route('home'),

        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckMfa;
use App\Http\Middleware\FactorSession;
use App\Http\Middleware\PasswordMiddleware;
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
            'admin' => AdminMiddleware::class,
            'factor' => FactorSession::class,
            'check.mfa' => CheckMfa::class,
            'changePassword' => PasswordMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

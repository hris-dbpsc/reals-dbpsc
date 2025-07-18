<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware){
        $middleware->alias([
            'superadmin' => \App\Http\Middleware\Superadmin::class,
            'admin' => \App\Http\Middleware\Admin::class,
            'clientadmin' => \App\Http\Middleware\Clientadmin::class,
            'user' => \App\Http\Middleware\User::class, // auth to user
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions){
        //
    })->create();

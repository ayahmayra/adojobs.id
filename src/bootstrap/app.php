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
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies (safe behind Nginx Proxy Manager)
        $middleware->trustProxies(at: '*');
        
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'employer' => \App\Http\Middleware\EmployerMiddleware::class,
            'seeker' => \App\Http\Middleware\SeekerMiddleware::class,
            'active' => \App\Http\Middleware\CheckActiveUser::class,
            'tester' => \App\Http\Middleware\CheckTester::class,
        ]);
        
        $middleware->web(append: [
            \App\Http\Middleware\CheckActiveUser::class,
            \App\Http\Middleware\CheckTesterWelcome::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

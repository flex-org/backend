<?php

use App\Http\Middleware\V1\SetLocale;
use Illuminate\Foundation\Application;
use App\Http\Middleware\V1\CheckDomainAccess;
use App\Http\Middleware\V1\CheckDomainExistances;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'locale' => SetLocale::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'domainExists' => CheckDomainExistances::class,
            'domainAccess' => CheckDomainAccess::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

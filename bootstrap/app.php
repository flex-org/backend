<?php

use App\Facades\ApiResponse;
use App\Http\Middleware\V1\CheckDomainAccess;
use App\Http\Middleware\V1\CheckDomainExistances;
use App\Http\Middleware\V1\SetLocale;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $exceptions->render(function (UnauthorizedException $e, $request) {
            return ApiResponse::unauthorized('auth.unauthorized');
        });
        $exceptions->render(function (AuthenticationException $e, $request) {
            return ApiResponse::unauthorized('auth.unauthorized');
        });

        $exceptions->render(function (AccessDeniedHttpException | AuthorizationException $e, $request) {
            return ApiResponse::forbidden();
        });

        $exceptions->render(function (NotFoundHttpException | ModelNotFoundException $e, $request) {
            return ApiResponse::notFound();
        });

        $exceptions->render(function (\DomainException $e, $request){
            return ApiResponse::message($e->getMessage(), 400);
        });

        $exceptions->render(function (HttpException $e, $request){
            return ApiResponse::message($e->getMessage(), $e->getStatusCode());
        });
    })->create();

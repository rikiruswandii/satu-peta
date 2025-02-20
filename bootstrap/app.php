<?php

use App\Http\Controllers\Api\Controller as ApiController;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware('api')->prefix('api')->as('api.')->group(base_path('routes/api.php'));
            Route::middleware('web')->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            \App\Http\Middleware\ViewShare::class,
            \App\Http\Middleware\SecurityHeader::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontReportDuplicates();

        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                $controller = new ApiController;
                $message = $e->getMessage();

                $code = $e instanceof HttpException
                    ? $e->getStatusCode()
                    : ($e instanceof AuthenticationException ? 401 : 500);

                return $controller->sendError($message, [], $code);
            }
        });
    })->create();

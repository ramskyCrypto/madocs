<?php

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\LogActivityLoginLogout;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            EnsureUserIsActive::class,
        ]);

        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function ($response, $exception, $request) {
            if (! app()->environment(['local', 'testing']) || $request->wantsJson() === false) {
                $status = $response->getStatusCode();

                if (in_array($status, [403, 404, 419, 500, 503]) && ! $request->expectsJson()) {
                    if (view()->exists("errors.{$status}")) {
                        return response()->view("errors.{$status}", ['exception' => $exception], $status);
                    }
                }
            }

            return $response;
        });
    })->create();

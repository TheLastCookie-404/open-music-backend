<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

const UNIQUE_VIOLATION = '23505';

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(
            function (TokenBlacklistedException | AuthenticationException $e, Request $request) {
                if ($request->is('api/*')) {
                    return response()->json([
                        'message' => $e->getMessage(),
                    ], Response::HTTP_UNAUTHORIZED);
                }
            }
        );
        $exceptions->render(
            function (ModelNotFoundException | NotFoundHttpException $e) {
                return response()->json([
                    'message' => 'Resource not found'
                ], Response::HTTP_NOT_FOUND);
            }
        );
        $exceptions->render(
            function (QueryException $e) {
                if ($e->getCode() === UNIQUE_VIOLATION) {
                    return response()->json([
                        'message' => 'Resource already exist'
                    ], Response::HTTP_NOT_FOUND);
                }
            }
        );
    })->create();

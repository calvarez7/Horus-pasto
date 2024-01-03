<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [];
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    public function register()
    {
        $this->renderable(function (Exception $e, $request) {
            return $this->handleException($request, $e);
        });
    }
    public function handleException($request, Exception $exception)
    {
        if ($exception instanceof RouteNotFoundException) {
            return response()->json([
                "res" => false,
                "message" => "Error al autenticar usuario!",
                'error' => $exception->getMessage()
            ], 401);
        }
        if($exception instanceof HttpException){
            return response()->json([
                "res" => false,
                "message" => "Error verifica la ruta no es encontrada!",
                'error' => $exception->getMessage()], 404);
        }
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                "res" => false,
                "message" => "Error usted no cuenta con permisos al modulo!",
                'error' => $exception->getMessage()], 403);
        }
    }
}

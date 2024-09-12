<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->view('livewire.error', [
                'titulo' => '404 - Página no encontrada.',
                'mensaje' => 'La página que estás buscando no está disponible.'
            ], 404);
        });

        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            if ($e->getStatusCode() == 403) {
                return response()->view('livewire.error', [
                    'titulo' => '403 - Acceso Denegado.',
                    'mensaje' => 'No tienes permiso para acceder a esta página.'
                ], 403);
            }
        });

// Error 500 - Internal Server Error
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            if ($e->getStatusCode() == 500) {
                return response()->view('livewire.error', [
                    'titulo' => '500 - Error del Servidor.',
                    'mensaje' => 'Ocurrió un error en el servidor, por favor intenta más tarde.'
                ], 500);
            }
        });

// Error 422 - Validation Error
        $exceptions->renderable(function (\Illuminate\Validation\ValidationException $e) {
            return response()->view('livewire.error', [
                'titulo' => '422 - Error de Validación.',
                'mensaje' => 'Los datos proporcionados no son válidos.'
            ], 422);
        });

// Error 503 - Service Unavailable
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            if ($e->getStatusCode() == 503) {
                return response()->view('livewire.error', [
                    'titulo' => '503 - Servicio no Disponible.',
                    'mensaje' => 'El servicio no está disponible en este momento, por favor intenta más tarde.'
                ], 503);
            }
        });

// Error 400 - Bad Request
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\BadRequestHttpException $e) {
            return response()->view('livewire.error', [
                'titulo' => '400 - Solicitud Incorrecta.',
                'mensaje' => 'La solicitud no se puede procesar debido a un error en los datos enviados.'
            ], 400);
        });

// Error 401 - Unauthorized
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException $e) {
            return response()->view('livewire.error', [
                'titulo' => '401 - No Autorizado.',
                'mensaje' => 'Necesitas autenticarte para acceder a esta página.'
            ], 401);
        });

// Error 405 - Method Not Allowed
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e) {
            return response()->view('livewire.error', [
                'titulo' => '405 - Método no Permitido.',
                'mensaje' => 'El método HTTP utilizado no está permitido para esta ruta.'
            ], 405);
        });

// Error 408 - Request Timeout
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            if ($e->getStatusCode() == 408) {
                return response()->view('livewire.error', [
                    'titulo' => '408 - Tiempo de Espera Agotado.',
                    'mensaje' => 'El servidor tardó demasiado en procesar la solicitud. Por favor, inténtalo de nuevo.'
                ], 408);
            }
        });

// Error 429 - Too Many Requests
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException $e) {
            return response()->view('livewire.error', [
                'titulo' => '429 - Demasiadas Solicitudes.',
                'mensaje' => 'Has enviado demasiadas solicitudes en un corto periodo de tiempo. Por favor, espera un momento antes de intentarlo nuevamente.'
            ], 429);
        });
    })->create();




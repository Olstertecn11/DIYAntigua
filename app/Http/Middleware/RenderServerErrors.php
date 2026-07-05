<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class RenderServerErrors
{
    /**
     * @param  Closure(Request): SymfonyResponse  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        try {
            return $next($request);
        } catch (Throwable $exception) {
            if (app()->environment(['local', 'testing'])) {
                throw $exception;
            }

            if ($exception instanceof HttpExceptionInterface && $exception->getStatusCode() < 500) {
                throw $exception;
            }

            report($exception);

            Log::error('Unhandled server error rendered as customer-safe response.', [
                'exception' => $exception,
                'request' => [
                    'method' => $request->method(),
                    'url' => $request->fullUrl(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'user_id' => $request->user()?->getAuthIdentifier(),
                ],
            ]);

            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => 'Ha ocurrido un error interno. Por favor intenta mas tarde.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->view('errors.500', [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

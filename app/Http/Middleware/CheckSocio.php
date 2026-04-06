<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSocio
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificamos si está logueado y si su rol es 2 (Socio)
        if (auth()->check() && auth()->user()->role_id == 2) {
            return $next($request);
        }

        return redirect('/socios/login')->with('error', 'No tienes permisos de socio.');
    }
}

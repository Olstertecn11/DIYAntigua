<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (Auth::check() && $user->isAdmin()) {
            return $next($request);
        }

        // Si no es admin, redirigimos con un mensaje de error
        return redirect('/admin')->with('error', 'No tienes permisos para acceder a esta sección.');
    }
}

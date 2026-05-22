<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PreventDuplicatePayment
{
    public function handle(Request $request, Closure $next): Response
    {
        $codigo = $request->route('codigo');
        $key = "payment-submit:{$codigo}:" . sha1((string) $request->session()->getId());
        $lock = Cache::lock($key, 60);

        if (! $lock->get()) {
            abort(409, 'Ya estamos procesando este pago.');
        }

        try {
            return $next($request);
        } finally {
            optional($lock)->release();
        }
    }
}

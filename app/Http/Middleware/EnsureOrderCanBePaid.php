<?php

namespace App\Http\Middleware;

use App\Models\Reservacion;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOrderCanBePaid
{
    public function handle(Request $request, Closure $next): Response
    {
        $codigo = $request->route('codigo');
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        if ($reservacion->estado_pago === 'pagado') {
            return redirect()->route('payments.success', $reservacion->codigo_reserva);
        }

        if (! in_array($reservacion->estado_pago, ['pendiente', 'rechazado', 'fallido', 'procesando'], true)) {
            abort(403, 'Esta reserva no puede pagarse en este momento.');
        }

        $request->attributes->set('reservacion', $reservacion);

        return $next($request);
    }
}

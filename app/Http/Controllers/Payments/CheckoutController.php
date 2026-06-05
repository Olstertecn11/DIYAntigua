<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Reservacion;
use App\Services\Payments\PaymentFingerprintService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function __construct(private readonly PaymentFingerprintService $fingerprintService)
    {
    }

    public function show(Request $request, string $codigo)
    {
        $reservacion = $request->attributes->get('reservacion')
            ?: Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        $fingerprintSessionId = $this->fingerprintService->ensureSessionId($reservacion);
        $fingerprintFullSessionId = config('qpaypro.fingerprint_prefix') . $fingerprintSessionId;

        return Inertia::render('Payments/Checkout', [
            'reserva' => [
                'codigo_reserva' => $reservacion->codigo_reserva,
                'nombre_cliente' => $reservacion->nombre_cliente,
                'fecha_viaje' => $reservacion->fecha_viaje?->format('d/m/Y'),
                'hora_viaje' => $reservacion->hora_viaje,
                'precio_total' => (float) $reservacion->precio_total,
            ],
            'defaults' => [
                'cc_name' => $reservacion->nombre_cliente,
                'billing_country' => 'Guatemala',
            ],
            'fingerprint' => [
                'sessionId' => $fingerprintSessionId,
                'fullSessionId' => $fingerprintFullSessionId,
                'orgId' => config('qpaypro.fingerprint_org_id'),
            ],
            'urls' => [
                'store' => route('payments.store', $reservacion->codigo_reserva),
                'reservation' => route('reservas.confirmar', $reservacion->codigo_reserva),
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Reservacion;
use App\Services\Payments\PaymentFingerprintService;
use Illuminate\Http\Request;

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

        return view('payments.checkout', [
            'reservacion' => $reservacion,
            'fingerprintSessionId' => $fingerprintSessionId,
            'fingerprintFullSessionId' => $fingerprintFullSessionId,
            'fingerprintOrgId' => config('qpaypro.fingerprint_org_id'),
        ]);
    }
}

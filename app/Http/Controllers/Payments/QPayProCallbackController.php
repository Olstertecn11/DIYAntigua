<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\QPayProCallbackRequest;
use App\Models\PaymentCallback;
use App\Models\Reservacion;
use App\Services\Payments\PaymentSanitizer;

class QPayProCallbackController extends Controller
{
    public function __construct(private readonly PaymentSanitizer $sanitizer)
    {
    }

    public function store(QPayProCallbackRequest $request)
    {
        $payload = $request->all();
        $reservacion = null;

        if ($request->filled('x_invoice_num')) {
            $reservacion = Reservacion::where('codigo_reserva', $request->string('x_invoice_num'))->first();
        }

        PaymentCallback::create([
            'provider' => 'qpaypro',
            'reservacion_id' => $reservacion?->id,
            'event_type' => 'qpaypro_callback',
            'status' => $request->input('status') ?: $request->input('response_code'),
            'payload_sanitized' => $this->sanitizer->sanitize($payload),
            'signature_valid' => false,
            'processed' => false,
        ]);

        return response()->json(['ok' => true]);
    }
}

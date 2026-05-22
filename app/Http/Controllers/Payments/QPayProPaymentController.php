<?php

namespace App\Http\Controllers\Payments;

use App\Enums\Payments\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\StoreCardPaymentRequest;
use App\Models\Reservacion;
use App\Services\Payments\PaymentManager;

class QPayProPaymentController extends Controller
{
    public function __construct(private readonly PaymentManager $paymentManager)
    {
    }

    public function store(StoreCardPaymentRequest $request, string $codigo)
    {
        $reservacion = $request->attributes->get('reservacion')
            ?: Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        $transaction = $this->paymentManager->pay($reservacion, $request->validated(), $request);

        return match ($transaction->status) {
            PaymentStatus::Approved->value => redirect()->route('payments.success', $reservacion->codigo_reserva),
            PaymentStatus::Declined->value => redirect()->route('payments.declined', $reservacion->codigo_reserva),
            PaymentStatus::Error->value => redirect()->route('payments.error', $reservacion->codigo_reserva),
            default => redirect()->route('payments.processing', $reservacion->codigo_reserva),
        };
    }
}

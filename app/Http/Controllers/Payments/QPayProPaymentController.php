<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payments\StoreCardPaymentRequest;
use App\Models\Reservacion;
use App\Services\Payments\PaymentManager;
use Illuminate\Support\Facades\URL;

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

        return redirect(URL::signedRoute('payments.result', ['transaction' => $transaction->id]));
    }
}

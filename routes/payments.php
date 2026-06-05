<?php

use App\Http\Controllers\Payments\CheckoutController;
use App\Http\Controllers\Payments\QPayProCallbackController;
use App\Http\Controllers\Payments\QPayProPaymentController;
use App\Http\Middleware\EnsureOrderCanBePaid;
use App\Http\Middleware\PreventDuplicatePayment;
use App\Models\PaymentTransaction;
use App\Models\Reservacion;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('pagos')->name('payments.')->group(function () {
    Route::middleware([EnsureOrderCanBePaid::class])->group(function () {
        Route::get('/reservas/{codigo}', [CheckoutController::class, 'show'])->name('checkout');
        Route::post('/reservas/{codigo}', [QPayProPaymentController::class, 'store'])
            ->middleware(['throttle:6,1', PreventDuplicatePayment::class])
            ->name('store');
    });

    Route::post('/qpaypro/callback', [QPayProCallbackController::class, 'store'])
        ->middleware('throttle:30,1')
        ->name('qpaypro.callback');

    Route::get('/retorno', function () {
        return redirect()->route('welcome');
    })->name('return');

    Route::get('/resultado/{transaction}', function (PaymentTransaction $transaction) {
        $transaction->loadMissing(['reservacion.ruta.origen', 'reservacion.ruta.destino']);

        $reservacion = $transaction->reservacion;

        return Inertia::render('Payments/Result', [
            'status' => $transaction->status,
            'message' => $transaction->response_message,
            'transaction' => [
                'provider_transaction_id' => $transaction->provider_transaction_id,
                'reference' => $transaction->reference,
                'status' => $transaction->status,
                'response_code' => $transaction->response_code,
                'response_message' => $transaction->response_message,
                'amount' => (float) $transaction->amount,
                'currency' => $transaction->currency,
                'card_brand' => $transaction->card_brand,
                'card_last_four' => $transaction->card_last_four,
            ],
            'reserva' => [
                'codigo_reserva' => $reservacion->codigo_reserva,
                'nombre_cliente' => $reservacion->nombre_cliente,
                'fecha_viaje' => $reservacion->fecha_viaje?->format('d/m/Y'),
                'hora_viaje' => $reservacion->hora_viaje,
                'tipo_vehiculo' => $reservacion->tipo_vehiculo,
                'pasajeros' => (int) $reservacion->pasajeros,
                'ruta' => [
                    'origen' => $reservacion->ruta?->origen?->nombre,
                    'destino' => $reservacion->ruta?->destino?->nombre,
                ],
                'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=10&data=' . urlencode(route('reservas.confirmar', $reservacion->codigo_reserva)),
            ],
            'urls' => [
                'checkout' => route('payments.checkout', $reservacion->codigo_reserva),
                'reservation' => route('reservas.confirmar', $reservacion->codigo_reserva),
                'pdf' => route('reservas.pdf', $reservacion->codigo_reserva),
            ],
        ]);
    })->middleware('signed')->name('result');

    Route::get('/reservas/{codigo}/procesando', function (string $codigo) {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        return Inertia::render('Payments/Status', [
            'variant' => 'processing',
            'title' => 'Pago en procesamiento',
            'message' => 'Estamos verificando el estado del pago.',
            'urls' => [
                'primary' => route('reservas.confirmar', $reservacion->codigo_reserva),
            ],
        ]);
    })->name('processing');

    Route::get('/reservas/{codigo}/exito', function (string $codigo) {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        return Inertia::render('Payments/Status', [
            'variant' => 'success',
            'title' => 'Pago aprobado',
            'message' => 'Tu reserva esta pagada y lista para coordinacion.',
            'urls' => [
                'primary' => route('reservas.confirmar', $reservacion->codigo_reserva),
                'secondary' => route('reservas.pdf', $reservacion->codigo_reserva),
            ],
        ]);
    })->name('success');

    Route::get('/reservas/{codigo}/rechazado', function (string $codigo) {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        return Inertia::render('Payments/Status', [
            'variant' => 'declined',
            'title' => 'Pago rechazado',
            'message' => 'El procesador no aprobo el cobro. Puedes intentar nuevamente.',
            'urls' => [
                'primary' => route('payments.checkout', $reservacion->codigo_reserva),
                'secondary' => route('reservas.confirmar', $reservacion->codigo_reserva),
            ],
        ]);
    })->name('declined');

    Route::get('/reservas/{codigo}/error', function (string $codigo) {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        return Inertia::render('Payments/Status', [
            'variant' => 'error',
            'title' => 'Error de pago',
            'message' => 'No pudimos completar el pago en este momento.',
            'urls' => [
                'primary' => route('payments.checkout', $reservacion->codigo_reserva),
                'secondary' => route('reservas.confirmar', $reservacion->codigo_reserva),
            ],
        ]);
    })->name('error');
});

<?php

use App\Http\Controllers\Payments\CheckoutController;
use App\Http\Controllers\Payments\QPayProCallbackController;
use App\Http\Controllers\Payments\QPayProPaymentController;
use App\Http\Middleware\EnsureOrderCanBePaid;
use App\Http\Middleware\PreventDuplicatePayment;
use App\Models\PaymentTransaction;
use App\Models\Reservacion;
use Illuminate\Support\Facades\Route;

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

        return view('payments.result', [
            'transaction' => $transaction,
            'reservacion' => $transaction->reservacion,
        ]);
    })->middleware('signed')->name('result');

    Route::get('/reservas/{codigo}/procesando', function (string $codigo) {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        return view('payments.processing', compact('reservacion'));
    })->name('processing');

    Route::get('/reservas/{codigo}/exito', function (string $codigo) {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        return view('payments.success', compact('reservacion'));
    })->name('success');

    Route::get('/reservas/{codigo}/rechazado', function (string $codigo) {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        return view('payments.declined', compact('reservacion'));
    })->name('declined');

    Route::get('/reservas/{codigo}/error', function (string $codigo) {
        $reservacion = Reservacion::where('codigo_reserva', $codigo)->firstOrFail();

        return view('payments.error', compact('reservacion'));
    })->name('error');
});

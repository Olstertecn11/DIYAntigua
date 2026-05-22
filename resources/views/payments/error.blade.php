@extends('layouts.app')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background:#050505;color:#fff;">
        <div class="text-center px-4" style="max-width:620px;">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width:96px;height:96px;border:2px solid #f59e0b;background:#0d0d0d;">
                <i class="fas fa-triangle-exclamation fa-3x text-warning"></i>
            </div>
            <h1 class="display-5 fw-bold mb-3">No se pudo procesar</h1>
            <p class="text-secondary mb-4">{{ $reservacion->pago_error_mensaje ?: 'El procesador no respondió como esperábamos. Puedes volver a intentar.' }}</p>
            <a href="{{ route('payments.checkout', $reservacion->codigo_reserva) }}" class="btn btn-warning rounded-pill py-3 px-5 fw-bold">Volver al pago</a>
        </div>
    </div>
@endsection

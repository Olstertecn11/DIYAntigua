@extends('layouts.app')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background:#050505;color:#fff;">
        <div class="text-center px-4" style="max-width:620px;">
            <div class="spinner-border text-warning mb-4" style="width:72px;height:72px;" role="status"></div>
            <h1 class="display-5 fw-bold mb-3">Pago en revisión</h1>
            <p class="text-secondary mb-4">La reserva {{ $reservacion->codigo_reserva }} quedó en estado de procesamiento. Verificaremos la transacción antes de confirmarla.</p>
            <a href="{{ route('reservas.confirmar', $reservacion->codigo_reserva) }}" class="btn btn-outline-light rounded-pill py-3 px-5">Ver reserva</a>
        </div>
    </div>
@endsection

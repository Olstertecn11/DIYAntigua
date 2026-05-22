@extends('layouts.app')

@section('content')
    <div class="min-vh-100 d-flex align-items-center justify-content-center py-5" style="background:#050505;color:#fff;">
        <div class="text-center px-4" style="max-width:620px;">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width:96px;height:96px;border:2px solid #22c55e;background:#0d0d0d;">
                <i class="fas fa-check fa-3x text-success"></i>
            </div>
            <h1 class="display-5 fw-bold mb-3">Pago aprobado</h1>
            <p class="text-secondary mb-4">Tu reserva {{ $reservacion->codigo_reserva }} quedó pagada correctamente.</p>
            <div class="d-grid gap-3">
                <a href="{{ route('reservas.confirmar', $reservacion->codigo_reserva) }}" class="btn btn-warning rounded-pill py-3 fw-bold">Ver reserva</a>
                <a href="{{ route('reservas.pdf', $reservacion->codigo_reserva) }}" class="btn btn-outline-light rounded-pill py-3">Descargar comprobante</a>
            </div>
        </div>
    </div>
@endsection

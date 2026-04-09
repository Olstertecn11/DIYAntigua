@extends('layouts.app')

@section('content')
<div class="min-h-screen d-flex align-items-center justify-content-center py-5" style="background-color: #050505; color: white;">
    <div class="text-center max-w-md px-4">
        <div class="mb-4">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg"
                 style="width: 100px; height: 100px; background-color: #0a0a0a; border: 2px solid #22c55e;">
                <i class="fas fa-check fa-3x text-success"></i>
            </div>
        </div>

        <h1 class="display-5 fw-bold mb-2 tracking-tighter uppercase">¡Reserva Recibida!</h1>
        <p class="text-muted mb-4">Hemos registrado tu solicitud con el código:</p>

        <div class="bg-[#0a0a0a] border border-[#262626] rounded-3 p-3 mb-5 inline-block mx-auto" style="background-color: #0a0a0a; border: 1px solid #262626;">
            <span class="h4 fw-mono text-warning font-monospace">{{ $reservacion->codigo_reserva }}</span>
        </div>

        <div class="card border-0 rounded-4 p-4 text-start mb-5" style="background-color: #0a0a0a; border: 1px solid #262626 !important;">
            <p class="small text-muted mb-3"><i class="fas fa-info-circle me-2"></i> ¿Qué sigue ahora?</p>
            <ul class="list-unstyled space-y-3">
                <li class="mb-2"><i class="fas fa-envelope text-success me-2"></i> Recibirás un correo de confirmación.</li>
                <li class="mb-2"><i class="fab fa-whatsapp text-success me-2"></i> Un agente te contactará para el pago.</li>
                <li><i class="fas fa-clock text-warning me-2"></i> Tu piloto te esperará en la fecha indicada.</li>
            </ul>
        </div>

        <div class="d-grid gap-3">
            <a href="/" class="btn btn-outline-light rounded-pill py-3 fw-bold uppercase text-xs tracking-widest">
                Volver al Inicio
            </a>
            <button onclick="window.print()" class="btn btn-link text-muted text-decoration-none small">
                <i class="fas fa-print me-1"></i> Descargar Comprobante PDF
            </button>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f7f7f2] py-10">
    <div class="container max-w-6xl">
        <div class="mb-8">
            <span class="inline-flex items-center rounded-full bg-[#FCCA00]/20 border border-[#FCCA00]/40 px-4 py-2 text-xs font-black uppercase tracking-widest text-[#363636]">
                <i class="fas fa-suitcase-rolling me-2"></i>
                Mi cuenta
            </span>
            <h1 class="display-5 fw-black mt-3 mb-2 text-black">Mis reservaciones</h1>
            <p class="lead text-muted mb-0">Historial de traslados, pagos, comprobantes y solicitudes de cancelación.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success rounded-4 fw-bold">{{ session('success') }}</div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger rounded-4 fw-bold">{{ $errors->first() }}</div>
        @endif

        <div class="row g-4">
            @forelse ($reservas as $reserva)
                @php
                    $travelAt = $reserva->travelDateTime();
                    $canCancel = $reserva->canBeCancelledWithRefund();
                @endphp
                <div class="col-12">
                    <article class="user-reservation-card">
                        <div>
                            <span class="reservation-code">{{ $reserva->codigo_reserva }}</span>
                            <h2>{{ $reserva->ruta?->origen?->nombre }} → {{ $reserva->ruta?->destino?->nombre }}</h2>
                            <p>{{ $travelAt->format('d/m/Y H:i') }} · {{ strtoupper($reserva->tipo_vehiculo) }} · {{ $reserva->pasajeros }} pasajero(s)</p>
                        </div>

                        <div class="reservation-statuses">
                            <span>{{ str_replace('_', ' ', $reserva->estado_pago) }}</span>
                            <span>{{ $reserva->estado_viaje }}</span>
                            <strong>Q{{ number_format((float) $reserva->precio_total, 2) }}</strong>
                        </div>

                        <div class="reservation-actions">
                            <span class="{{ $canCancel ? 'policy-ok' : 'policy-locked' }}">
                                {{ $canCancel ? 'Cancelable con reembolso' : 'Fuera de política 24h' }}
                            </span>
                            <a href="{{ route('reservas.mine.show', $reserva) }}">Ver reserva</a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-reservations">
                        <i class="fas fa-calendar-check"></i>
                        <h2>Aún no tienes reservaciones</h2>
                        <p>Cuando reserves con tu cuenta, tus traslados aparecerán aquí.</p>
                        <a href="{{ url('/') }}#booking">Reservar traslado</a>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($reservas->hasPages())
            <div class="mt-5">
                {{ $reservas->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .user-reservation-card {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto auto;
        gap: 22px;
        align-items: center;
        padding: 24px;
        border-radius: 24px;
        background: #fff;
        border: 1px solid rgba(54,54,54,.12);
        box-shadow: 0 24px 70px rgba(0,0,0,.08);
    }

    .reservation-code {
        color: #b89500;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .14em;
    }

    .user-reservation-card h2 {
        margin: 6px 0;
        color: #000;
        font-size: 24px;
        font-weight: 950;
    }

    .user-reservation-card p {
        margin: 0;
        color: #666;
        font-weight: 700;
    }

    .reservation-statuses,
    .reservation-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: flex-end;
    }

    .reservation-statuses span,
    .reservation-actions span {
        padding: 7px 10px;
        border-radius: 999px;
        background: #f6f6f3;
        color: #363636;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .reservation-statuses strong {
        color: #000;
        font-size: 22px;
        font-weight: 950;
    }

    .reservation-actions a,
    .empty-reservations a {
        display: inline-flex;
        justify-content: center;
        border-radius: 999px;
        padding: 12px 18px;
        background: #FCCA00;
        color: #000;
        text-decoration: none;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .reservation-actions .policy-ok {
        color: #166534;
        background: #dcfce7;
    }

    .reservation-actions .policy-locked {
        color: #991b1b;
        background: #fee2e2;
    }

    .empty-reservations {
        padding: 64px 24px;
        border-radius: 28px;
        background: #fff;
        border: 1px solid rgba(54,54,54,.12);
        text-align: center;
    }

    .empty-reservations i {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #FCCA00;
        color: #000;
        font-size: 28px;
        margin-bottom: 18px;
    }

    .empty-reservations h2 {
        color: #000;
        font-weight: 950;
    }

    .empty-reservations p {
        color: #666;
        margin-bottom: 24px;
    }

    @media (max-width: 900px) {
        .user-reservation-card {
            grid-template-columns: 1fr;
        }

        .reservation-statuses,
        .reservation-actions {
            align-items: flex-start;
        }
    }
</style>
@endsection

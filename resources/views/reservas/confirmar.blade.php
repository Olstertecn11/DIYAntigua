@extends('layouts.app')

@section('content')
@php
    $reserva = $reservacion;
    $travelAt = $reserva->travelDateTime();
    $transaction = $reserva->paymentTransactions->first();
    $isPaid = $reserva->estado_pago === 'pagado';
    $qrPayload = route('reservas.confirmar', $reserva->codigo_reserva);
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=10&data=' . urlencode($qrPayload);
@endphp

<div class="guest-reservation-page">
    <div class="container max-w-6xl">
        <a href="{{ route('welcome') }}" class="back-link">
            <i class="fas fa-arrow-left me-2"></i>
            Inicio
        </a>

        @if (session('success'))
            <div class="alert alert-success rounded-4 fw-bold mt-4">{{ session('success') }}</div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger rounded-4 fw-bold mt-4">{{ $errors->first() }}</div>
        @endif

        <section class="reservation-hero-card">
            <div>
                <span class="reservation-kicker">Comprobante de reserva</span>
                <h1>{{ $reserva->codigo_reserva }}</h1>
                <p>{{ $reserva->ruta?->origen?->nombre }} → {{ $reserva->ruta?->destino?->nombre }}</p>
            </div>

            <div class="countdown-box" data-travel-at="{{ $travelAt->toIso8601String() }}">
                <span>Tiempo restante</span>
                <strong id="reservation-countdown">{{ $reserva->estado_viaje === 'cancelado' ? 'Cancelada' : 'Calculando...' }}</strong>
                <small>{{ $travelAt->format('d/m/Y H:i') }}</small>
            </div>
        </section>

        <div class="reservation-detail-grid">
            <section class="reservation-panel">
                <div class="panel-title-row">
                    <div>
                        <span class="reservation-kicker">Traslado privado</span>
                        <h2>Detalles del viaje</h2>
                    </div>
                    <span class="status-pill {{ $isPaid ? 'paid' : 'pending' }}">
                        Pago: {{ str_replace('_', ' ', $reserva->estado_pago) }}
                    </span>
                </div>

                <div class="route-summary">
                    <div>
                        <span>Origen</span>
                        <strong>{{ $reserva->ruta?->origen?->nombre ?? 'Pendiente' }}</strong>
                    </div>
                    <i class="fas fa-van-shuttle"></i>
                    <div>
                        <span>Destino</span>
                        <strong>{{ $reserva->ruta?->destino?->nombre ?? 'Pendiente' }}</strong>
                    </div>
                </div>

                <div class="detail-grid">
                    <div><span>Pasajero</span><strong>{{ $reserva->nombre_cliente }}</strong></div>
                    <div><span>Teléfono</span><strong>{{ $reserva->telefono_cliente }}</strong></div>
                    <div><span>Correo</span><strong>{{ $reserva->correo_cliente }}</strong></div>
                    <div><span>Fecha</span><strong>{{ $travelAt->format('d/m/Y') }}</strong></div>
                    <div><span>Hora</span><strong>{{ $travelAt->format('H:i') }}</strong></div>
                    <div><span>Vehículo</span><strong>{{ strtoupper($reserva->tipo_vehiculo) }}</strong></div>
                    <div><span>Pasajeros</span><strong>{{ $reserva->pasajeros }}</strong></div>
                    <div><span>Total</span><strong>Q{{ number_format((float) $reserva->precio_total, 2) }}</strong></div>
                </div>

                <div class="notes-box">
                    <span>Puntos y notas</span>
                    <p>{{ $reserva->notas_adicionales ?: 'Sin notas adicionales.' }}</p>
                </div>
            </section>

            <aside class="reservation-panel side-panel">
                <h2>Estado y comprobante</h2>

                <div class="qr-box">
                    <img src="{{ $qrUrl }}" alt="QR de reserva">
                    <span>Validación digital</span>
                    <strong>{{ $reserva->codigo_reserva }}</strong>
                </div>

                <div class="status-stack">
                    <span>Pago: {{ str_replace('_', ' ', $reserva->estado_pago) }}</span>
                    <span>Viaje: {{ $reserva->estado_viaje }}</span>
                    <span>Reserva: {{ $reserva->created_at?->format('d/m/Y H:i') }}</span>
                </div>

                @if ($transaction)
                    <div class="payment-mini">
                        <span>Transacción</span>
                        <strong>{{ $transaction->provider_transaction_id ?: $transaction->reference }}</strong>
                        <small>{{ strtoupper($transaction->card_brand ?: 'CARD') }} **** {{ $transaction->card_last_four ?: '----' }}</small>
                    </div>
                @endif

                <div class="policy-box {{ $isPaid ? 'ok' : 'locked' }}">
                    <i class="fas {{ $isPaid ? 'fa-circle-check' : 'fa-circle-info' }}"></i>
                    <div>
                        <strong>{{ $isPaid ? 'Pago confirmado' : 'Pago pendiente' }}</strong>
                        <p>{{ $isPaid ? 'Tu traslado está pagado y listo para coordinación.' : 'Puedes completar el pago para dejar tu reserva confirmada.' }}</p>
                    </div>
                </div>

                <div class="action-stack">
                    <a href="{{ route('reservas.pdf', $reserva->codigo_reserva) }}" class="primary-action">
                        Descargar comprobante
                    </a>
                    @if (! $isPaid)
                        <a href="{{ route('payments.checkout', $reserva->codigo_reserva) }}" class="secondary-action">
                            Pagar reserva
                        </a>
                    @endif
                    <a href="{{ route('welcome') }}#booking" class="ghost-action">
                        Nueva cotización
                    </a>
                </div>
            </aside>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const box = document.querySelector('.countdown-box');
        const target = document.getElementById('reservation-countdown');

        if (!box || !target || target.textContent === 'Cancelada') {
            return;
        }

        const travelAt = new Date(box.dataset.travelAt).getTime();

        function updateCountdown() {
            const diff = travelAt - Date.now();

            if (diff <= 0) {
                target.textContent = 'En curso';
                return;
            }

            const days = Math.floor(diff / 86400000);
            const hours = Math.floor((diff % 86400000) / 3600000);
            const minutes = Math.floor((diff % 3600000) / 60000);

            target.textContent = `${days}d ${hours}h ${minutes}m`;
        }

        updateCountdown();
        setInterval(updateCountdown, 60000);
    });
</script>

<style>
    .guest-reservation-page {
        min-height: 100vh;
        padding: 42px 0;
        background:
            linear-gradient(90deg, #FCCA00 0 12px, transparent 12px),
            linear-gradient(135deg, #ffffff 0%, #f7f7f2 58%, #ecebe5 100%);
    }

    .back-link {
        color: #363636;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .1em;
        text-decoration: none;
    }

    .reservation-hero-card,
    .reservation-panel {
        background: rgba(255, 255, 255, .92);
        border: 1px solid rgba(54, 54, 54, .12);
        box-shadow: 0 24px 70px rgba(0, 0, 0, .08);
        border-radius: 28px;
    }

    .reservation-hero-card {
        margin-top: 20px;
        padding: 32px;
        display: flex;
        justify-content: space-between;
        gap: 24px;
        align-items: center;
    }

    .reservation-kicker {
        color: #b89500;
        font-size: 12px;
        font-weight: 950;
        letter-spacing: .16em;
        text-transform: uppercase;
    }

    .reservation-hero-card h1 {
        margin: 8px 0;
        color: #000000;
        font-size: clamp(34px, 6vw, 64px);
        font-weight: 950;
        line-height: 1;
    }

    .reservation-hero-card p {
        color: #5f5f5f;
        font-size: 18px;
        font-weight: 800;
        margin: 0;
    }

    .countdown-box {
        min-width: 220px;
        padding: 22px;
        border-radius: 22px;
        background: #000000;
        color: #ffffff;
        text-align: center;
    }

    .countdown-box span,
    .countdown-box small,
    .detail-grid span,
    .notes-box span,
    .payment-mini span,
    .route-summary span,
    .qr-box span {
        display: block;
        color: #777777;
        font-size: 11px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .12em;
    }

    .countdown-box strong {
        display: block;
        color: #FCCA00;
        font-size: 30px;
        font-weight: 950;
        margin: 6px 0;
    }

    .reservation-detail-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 360px;
        gap: 24px;
        margin-top: 24px;
    }

    .reservation-panel {
        padding: 28px;
    }

    .panel-title-row {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .reservation-panel h2 {
        color: #000000;
        font-size: 22px;
        font-weight: 950;
        margin: 6px 0 0;
    }

    .status-pill {
        border-radius: 999px;
        padding: 10px 14px;
        color: #000000;
        font-size: 11px;
        font-weight: 950;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .status-pill.paid {
        background: #bbf7d0;
    }

    .status-pill.pending {
        background: #fef3c7;
    }

    .route-summary {
        display: grid;
        grid-template-columns: 1fr auto 1fr;
        gap: 18px;
        align-items: center;
        margin-bottom: 24px;
        padding: 18px;
        border-radius: 20px;
        background: #000000;
        color: #ffffff;
    }

    .route-summary i {
        color: #FCCA00;
    }

    .route-summary strong {
        color: #ffffff;
        font-size: 18px;
        font-weight: 950;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .detail-grid strong {
        display: block;
        color: #000000;
        font-size: 16px;
        font-weight: 950;
        margin-top: 4px;
        word-break: break-word;
    }

    .notes-box,
    .payment-mini,
    .policy-box,
    .qr-box {
        margin-top: 18px;
        padding: 18px;
        border-radius: 18px;
        background: #f6f6f3;
        border: 1px solid rgba(54, 54, 54, .10);
    }

    .notes-box p {
        margin: 8px 0 0;
        color: #363636;
        white-space: pre-wrap;
    }

    .qr-box {
        margin-top: 0;
        text-align: center;
    }

    .qr-box img {
        width: 150px;
        height: 150px;
        margin-bottom: 12px;
    }

    .qr-box strong,
    .payment-mini strong {
        display: block;
        color: #000000;
        font-weight: 950;
        word-break: break-all;
        margin-top: 6px;
    }

    .status-stack {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 18px;
    }

    .status-stack span {
        padding: 10px 12px;
        border-radius: 999px;
        background: #f6f6f3;
        color: #363636;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
    }

    .policy-box {
        display: flex;
        gap: 12px;
    }

    .policy-box i {
        margin-top: 3px;
    }

    .policy-box.ok {
        background: #dcfce7;
        color: #166534;
    }

    .policy-box.locked {
        background: #fef3c7;
        color: #92400e;
    }

    .policy-box strong {
        display: block;
        font-weight: 950;
    }

    .policy-box p {
        margin: 4px 0 0;
        font-size: 13px;
        font-weight: 700;
    }

    .action-stack {
        margin-top: 18px;
        display: grid;
        gap: 12px;
    }

    .primary-action,
    .secondary-action,
    .ghost-action {
        width: 100%;
        border: 0;
        border-radius: 999px;
        padding: 14px 18px;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .08em;
        text-align: center;
        text-decoration: none;
    }

    .primary-action {
        background: #FCCA00;
        color: #000000;
    }

    .secondary-action {
        background: #000000;
        color: #ffffff;
    }

    .ghost-action {
        border: 1px solid rgba(54, 54, 54, .14);
        background: #ffffff;
        color: #363636;
    }

    @media (max-width: 980px) {
        .reservation-hero-card,
        .reservation-detail-grid {
            grid-template-columns: 1fr;
        }

        .reservation-hero-card {
            flex-direction: column;
            align-items: stretch;
        }

        .detail-grid,
        .route-summary {
            grid-template-columns: 1fr;
        }

        .route-summary i {
            display: none;
        }

        .panel-title-row {
            flex-direction: column;
        }

        .status-pill {
            white-space: normal;
        }
    }
</style>
@endsection

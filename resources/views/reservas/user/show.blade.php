@extends('layouts.app')

@section('content')
@php
    $travelAt = $reserva->travelDateTime();
    $canCancel = $reserva->canBeCancelledWithRefund();
    $transaction = $reserva->paymentTransactions->first();
@endphp

<div class="my-reservation-page">
    <div class="container max-w-6xl">
        <a href="{{ route('reservas.mine.index') }}" class="back-link">
            <i class="fas fa-arrow-left me-2"></i>
            Mis reservaciones
        </a>

        @if (session('success'))
            <div class="alert alert-success rounded-4 fw-bold mt-4">{{ session('success') }}</div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger rounded-4 fw-bold mt-4">{{ $errors->first() }}</div>
        @endif

        <section class="reservation-hero-card">
            <div>
                <span class="reservation-kicker">Mi reservación</span>
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
                <h2>Detalles del traslado</h2>
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
                    <span>Notas y puntos</span>
                    <p>{{ $reserva->notas_adicionales ?: 'Sin notas adicionales.' }}</p>
                </div>
            </section>

            <aside class="reservation-panel side-panel">
                <h2>Estado</h2>
                <div class="status-stack">
                    <span>Pago: {{ str_replace('_', ' ', $reserva->estado_pago) }}</span>
                    <span>Viaje: {{ $reserva->estado_viaje }}</span>
                    <span>Reembolso: {{ $reserva->reembolso_estado ? str_replace('_', ' ', $reserva->reembolso_estado) : 'sin solicitud' }}</span>
                </div>

                @if ($transaction)
                    <div class="payment-mini">
                        <span>Referencia</span>
                        <strong>{{ $transaction->provider_transaction_id ?: $transaction->reference }}</strong>
                        <small>{{ strtoupper($transaction->card_brand ?: 'CARD') }} **** {{ $transaction->card_last_four }}</small>
                    </div>
                @endif

                <div class="policy-box {{ $canCancel ? 'ok' : 'locked' }}">
                    <i class="fas {{ $canCancel ? 'fa-circle-check' : 'fa-lock' }}"></i>
                    <div>
                        <strong>{{ $canCancel ? 'Cancelable con reembolso' : 'Cancelación cerrada' }}</strong>
                        <p>{{ $canCancel ? 'Puedes cancelar porque faltan más de 24 horas para el viaje.' : 'Ya no cumple la política de más de 24 horas antes del viaje.' }}</p>
                    </div>
                </div>

                <div class="action-stack">
                    <a href="{{ route('reservas.pdf', $reserva->codigo_reserva) }}" class="primary-action">
                        Descargar comprobante
                    </a>
                    @if ($canCancel)
                        <button type="button" id="open-cancel-modal" class="danger-action">Cancelar reservación</button>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</div>

@if ($canCancel)
    <div id="cancel-modal" class="cancel-modal hidden">
        <div class="cancel-modal-card">
            <div class="cancel-modal-icon">
                <i class="fas fa-rotate-left"></i>
            </div>
            <p class="reservation-kicker">Política de reembolso</p>
            <h2>Cancelar reservación</h2>
            <p class="cancel-modal-copy">
                Tu reservación cumple la política de más de 24 horas antes del viaje. Al confirmar, la reserva se cancelará y el reembolso quedará en revisión administrativa.
            </p>
            <div class="cancel-modal-summary">
                <div><span>Reserva</span><strong>{{ $reserva->codigo_reserva }}</strong></div>
                <div><span>Viaje</span><strong>{{ $travelAt->format('d/m/Y H:i') }}</strong></div>
                <div><span>Monto</span><strong>Q{{ number_format((float) $reserva->precio_total, 2) }}</strong></div>
            </div>
            <form action="{{ route('reservas.mine.cancel', $reserva) }}" method="POST">
                @csrf
                <textarea name="motivo_cancelacion" maxlength="500" rows="3" placeholder="Motivo opcional"></textarea>
                <div class="cancel-modal-actions">
                    <button type="button" id="cancel-modal-close" class="secondary-action">Volver</button>
                    <button class="danger-action">Confirmar cancelación</button>
                </div>
            </form>
        </div>
    </div>
@endif

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

        const modal = document.getElementById('cancel-modal');
        const open = document.getElementById('open-cancel-modal');
        const close = document.getElementById('cancel-modal-close');

        open?.addEventListener('click', function () {
            modal?.classList.remove('hidden');
        });

        close?.addEventListener('click', function () {
            modal?.classList.add('hidden');
        });

        modal?.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>

<style>
    .my-reservation-page {
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
        background: rgba(255,255,255,.92);
        border: 1px solid rgba(54,54,54,.12);
        box-shadow: 0 24px 70px rgba(0,0,0,.08);
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
        color: #000;
        font-size: clamp(36px, 6vw, 64px);
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
        background: #000;
        color: #fff;
        text-align: center;
    }

    .countdown-box span,
    .countdown-box small,
    .detail-grid span,
    .notes-box span,
    .payment-mini span {
        display: block;
        color: #777;
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

    .reservation-panel h2 {
        color: #000;
        font-size: 22px;
        font-weight: 950;
        margin-bottom: 20px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .detail-grid strong {
        display: block;
        color: #000;
        font-size: 16px;
        font-weight: 950;
        margin-top: 4px;
        word-break: break-word;
    }

    .notes-box {
        margin-top: 24px;
        padding: 18px;
        border-radius: 18px;
        background: #f6f6f3;
        border: 1px solid rgba(54,54,54,.10);
    }

    .notes-box p {
        margin: 8px 0 0;
        color: #363636;
        white-space: pre-wrap;
    }

    .status-stack {
        display: flex;
        flex-direction: column;
        gap: 10px;
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

    .payment-mini,
    .policy-box {
        margin-top: 18px;
        padding: 18px;
        border-radius: 18px;
        background: #f6f6f3;
        border: 1px solid rgba(54,54,54,.10);
    }

    .payment-mini strong {
        display: block;
        color: #000;
        font-weight: 950;
        word-break: break-all;
        margin-top: 6px;
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
        background: #fee2e2;
        color: #991b1b;
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
    .danger-action {
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
        color: #000;
    }

    .danger-action {
        background: #dc2626;
        color: #fff;
        margin-top: 8px;
    }

    .secondary-action {
        border: 1px solid rgba(54,54,54,.14);
        background: #fff;
        color: #363636;
        border-radius: 999px;
        padding: 14px 18px;
        font-size: 12px;
        font-weight: 950;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .action-stack textarea {
        width: 100%;
        border-radius: 16px;
        border: 1px solid rgba(54,54,54,.14);
        padding: 12px;
        resize: vertical;
    }

    .cancel-modal {
        position: fixed;
        inset: 0;
        z-index: 120;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 18px;
        background: rgba(0,0,0,.72);
        backdrop-filter: blur(14px);
    }

    .cancel-modal.hidden {
        display: none;
    }

    .cancel-modal-card {
        width: min(560px, 100%);
        background: #fff;
        border-radius: 28px;
        padding: 28px;
        box-shadow: 0 30px 90px rgba(0,0,0,.28);
    }

    .cancel-modal-icon {
        width: 52px;
        height: 52px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fee2e2;
        color: #dc2626;
        margin-bottom: 16px;
    }

    .cancel-modal-card h2 {
        color: #000;
        font-weight: 950;
        margin: 8px 0;
    }

    .cancel-modal-copy {
        color: #5f5f5f;
        font-weight: 700;
        line-height: 1.6;
    }

    .cancel-modal-summary {
        display: grid;
        gap: 10px;
        margin: 18px 0;
        padding: 16px;
        border-radius: 18px;
        background: #f6f6f3;
    }

    .cancel-modal-summary div {
        display: flex;
        justify-content: space-between;
        gap: 18px;
    }

    .cancel-modal-summary span {
        color: #777;
        font-size: 11px;
        font-weight: 950;
        text-transform: uppercase;
    }

    .cancel-modal-summary strong {
        color: #000;
        text-align: right;
    }

    .cancel-modal-card textarea {
        width: 100%;
        border-radius: 16px;
        border: 1px solid rgba(54,54,54,.14);
        padding: 12px;
        resize: vertical;
    }

    .cancel-modal-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 14px;
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

        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@extends('layouts.app')

@php
    $isApproved = $transaction->status === 'approved';
    $isDeclined = $transaction->status === 'declined';
    $statusLabel = match ($transaction->status) {
        'approved' => 'Pago aprobado',
        'declined' => 'Pago rechazado',
        'error' => 'No se pudo procesar',
        'under_review' => 'Pago en revisión',
        default => 'Pago en procesamiento',
    };
    $statusColor = $isApproved ? '#22c55e' : ($isDeclined ? '#ef4444' : '#facc15');
    $qrPayload = route('reservas.confirmar', $reservacion->codigo_reserva);
    $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=10&data=' . urlencode($qrPayload);
@endphp

@section('content')
    <div class="ticket-result-page">
        <div class="ticket-shell">
            <div class="ticket-hero">
                <div>
                    <span class="ticket-kicker">DIY Antigua Private Transfers</span>
                    <h1>{{ $statusLabel }}</h1>
                    <p>
                        @if ($isApproved)
                            Tu traslado quedó confirmado. Presenta esta constancia digital el día de tu viaje.
                        @elseif ($isDeclined)
                            La reserva fue creada, pero el cobro no fue aprobado por el procesador.
                        @else
                            La reserva fue creada y el pago requiere revisión o un nuevo intento.
                        @endif
                    </p>
                </div>

                <div class="ticket-status" style="border-color: {{ $statusColor }}; color: {{ $statusColor }};">
                    <i class="fas {{ $isApproved ? 'fa-check' : ($isDeclined ? 'fa-xmark' : 'fa-clock') }}"></i>
                    {{ strtoupper($transaction->status) }}
                </div>
            </div>

            <section class="boarding-pass">
                <div class="boarding-main">
                    <div class="route-row">
                        <div>
                            <span>Origen</span>
                            <strong>{{ $reservacion->ruta?->origen?->nombre ?? 'Origen' }}</strong>
                        </div>
                        <div class="route-plane">
                            <i class="fas fa-van-shuttle"></i>
                        </div>
                        <div>
                            <span>Destino</span>
                            <strong>{{ $reservacion->ruta?->destino?->nombre ?? 'Destino' }}</strong>
                        </div>
                    </div>

                    <div class="ticket-grid">
                        <div>
                            <span>Pasajero</span>
                            <strong>{{ $reservacion->nombre_cliente }}</strong>
                        </div>
                        <div>
                            <span>Reserva</span>
                            <strong>{{ $reservacion->codigo_reserva }}</strong>
                        </div>
                        <div>
                            <span>Fecha</span>
                            <strong>{{ \Carbon\Carbon::parse($reservacion->fecha_viaje)->format('d/m/Y') }}</strong>
                        </div>
                        <div>
                            <span>Hora</span>
                            <strong>{{ $reservacion->hora_viaje }}</strong>
                        </div>
                        <div>
                            <span>Vehículo</span>
                            <strong>{{ strtoupper($reservacion->tipo_vehiculo) }}</strong>
                        </div>
                        <div>
                            <span>Pasajeros</span>
                            <strong>{{ $reservacion->pasajeros }}</strong>
                        </div>
                    </div>

                    <div class="payment-band">
                        <div>
                            <span>Total</span>
                            <strong>Q{{ number_format($transaction->amount, 2) }}</strong>
                        </div>
                        <div>
                            <span>Referencia</span>
                            <strong>{{ $transaction->provider_transaction_id ?: $transaction->reference }}</strong>
                        </div>
                        <div>
                            <span>Tarjeta</span>
                            <strong>{{ strtoupper($transaction->card_brand ?: 'CARD') }} •••• {{ $transaction->card_last_four }}</strong>
                        </div>
                    </div>
                </div>

                <aside class="boarding-side">
                    <img src="{{ $qrUrl }}" alt="QR de reserva">
                    <span>Escanea para validar</span>
                    <strong>{{ $reservacion->codigo_reserva }}</strong>
                </aside>
            </section>

            @if (! $isApproved)
                <div class="ticket-alert">
                    <i class="fas fa-circle-info"></i>
                    <span>{{ $transaction->response_message ?: 'Puedes intentar el pago nuevamente sin volver a llenar toda la información.' }}</span>
                </div>
            @endif

            <div class="ticket-actions">
                @if (! $isApproved)
                    <a href="{{ route('payments.checkout', $reservacion->codigo_reserva) }}" class="btn-primary-ticket">Intentar pago nuevamente</a>
                @endif
                <a href="{{ route('reservas.confirmar', $reservacion->codigo_reserva) }}" class="btn-secondary-ticket">Ver reserva</a>
                <a href="{{ route('reservas.pdf', $reservacion->codigo_reserva) }}" class="btn-secondary-ticket">Descargar PDF</a>
            </div>
        </div>
    </div>

    <style>
        .ticket-result-page {
            min-height: 100vh;
            background:
                linear-gradient(135deg, rgba(2,6,23,.94), rgba(7,7,7,.92)),
                url('{{ asset('images/car_trip.jpg') }}') center/cover no-repeat;
            padding: 56px 16px;
            color: white;
        }

        .ticket-shell {
            max-width: 1060px;
            margin: 0 auto;
        }

        .ticket-hero {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 24px;
            margin-bottom: 24px;
        }

        .ticket-kicker {
            color: #facc15;
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .16em;
        }

        .ticket-hero h1 {
            font-size: clamp(36px, 6vw, 64px);
            line-height: 1;
            font-weight: 950;
            margin: 10px 0 12px;
        }

        .ticket-hero p {
            color: rgba(255,255,255,.72);
            max-width: 620px;
            margin: 0;
            font-size: 17px;
        }

        .ticket-status {
            border: 1px solid;
            border-radius: 999px;
            padding: 12px 18px;
            font-size: 12px;
            font-weight: 950;
            letter-spacing: .12em;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(0,0,0,.38);
            white-space: nowrap;
        }

        .boarding-pass {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 260px;
            border-radius: 28px;
            overflow: hidden;
            background: #f8fafc;
            color: #0f172a;
            box-shadow: 0 30px 80px rgba(0,0,0,.42);
        }

        .boarding-main {
            padding: 34px;
        }

        .route-row {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 22px;
            padding-bottom: 28px;
            border-bottom: 1px dashed rgba(15,23,42,.22);
        }

        .route-row span,
        .ticket-grid span,
        .payment-band span,
        .boarding-side span {
            display: block;
            color: #64748b;
            font-size: 11px;
            font-weight: 950;
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: 6px;
        }

        .route-row strong {
            font-size: clamp(22px, 4vw, 36px);
            line-height: 1.05;
            font-weight: 950;
        }

        .route-row div:last-child {
            text-align: right;
        }

        .route-plane {
            width: 58px;
            height: 58px;
            border-radius: 999px;
            background: linear-gradient(135deg, #facc15, #fb923c);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #020617;
            font-size: 22px;
        }

        .ticket-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
            padding: 28px 0;
        }

        .ticket-grid strong,
        .payment-band strong {
            font-size: 16px;
            font-weight: 950;
            word-break: break-word;
        }

        .payment-band {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            background: #0f172a;
            color: white;
            border-radius: 18px;
            padding: 20px;
        }

        .payment-band span {
            color: #94a3b8;
        }

        .boarding-side {
            background: #0f172a;
            color: white;
            padding: 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }

        .boarding-side::before,
        .boarding-side::after {
            content: "";
            position: absolute;
            left: -16px;
            width: 32px;
            height: 32px;
            background: rgba(2,6,23,.94);
            border-radius: 999px;
        }

        .boarding-side::before {
            top: -16px;
        }

        .boarding-side::after {
            bottom: -16px;
        }

        .boarding-side img {
            width: 170px;
            height: 170px;
            background: white;
            border-radius: 18px;
            padding: 10px;
            margin-bottom: 16px;
        }

        .boarding-side strong {
            color: #facc15;
            font-size: 18px;
        }

        .ticket-alert {
            margin-top: 18px;
            padding: 16px 18px;
            border-radius: 18px;
            background: rgba(250,204,21,.14);
            border: 1px solid rgba(250,204,21,.28);
            color: #fde68a;
            display: flex;
            gap: 12px;
            align-items: center;
            font-weight: 800;
        }

        .ticket-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .btn-primary-ticket,
        .btn-secondary-ticket {
            border-radius: 999px;
            padding: 14px 22px;
            font-weight: 950;
            text-decoration: none;
        }

        .btn-primary-ticket {
            background: linear-gradient(135deg, #facc15, #fb923c);
            color: #020617;
        }

        .btn-secondary-ticket {
            border: 1px solid rgba(255,255,255,.18);
            color: white;
            background: rgba(255,255,255,.08);
        }

        @media (max-width: 860px) {
            .ticket-hero {
                align-items: flex-start;
                flex-direction: column;
            }

            .boarding-pass {
                grid-template-columns: 1fr;
            }

            .boarding-side::before,
            .boarding-side::after {
                display: none;
            }

            .ticket-grid,
            .payment-band {
                grid-template-columns: 1fr;
            }

            .route-row {
                grid-template-columns: 1fr;
                text-align: left;
            }

            .route-row div:last-child {
                text-align: left;
            }
        }
    </style>
@endsection

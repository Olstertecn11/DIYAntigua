@extends('layouts.app')

@section('content')
    <div class="vehicles-page min-h-screen py-5">
        <div class="container position-relative">

            {{-- Encabezado superior --}}
            <div class="row mb-5">
                <div class="col-lg-8">
                    <span class="eyebrow-badge mb-3">
                        <i class="fas fa-car-side me-2"></i>
                        Transporte privado
                    </span>

                    <h1 class="display-5 fw-black mb-3 text-white">
                        Vehículos disponibles
                    </h1>

                    <p class="lead mb-0 text-soft">
                        Transporte privado, cómodo y seguro para la ruta seleccionada.
                        Elige la opción que mejor se adapte a tu viaje.
                    </p>
                </div>
            </div>

            <div class="row g-4">

                {{-- Resumen del viaje --}}
                <div class="col-lg-4 order-lg-2 mb-4">
                    <div class="trip-summary-card sticky-lg-top">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div>
                                <span class="section-kicker">Resumen</span>
                                <h5 class="fw-black mb-0 text-white">
                                    Resumen del viaje
                                </h5>
                            </div>

                            <div class="summary-icon">
                                <i class="fas fa-route"></i>
                            </div>
                        </div>

                        <div class="summary-block mb-4">
                            <label class="summary-label">
                                Ruta seleccionada
                            </label>

                            <div class="route-box mt-2">
                                <div class="route-point">
                                    <span class="route-dot"></span>
                                    <span>{{ $ruta->origen->nombre }}</span>
                                </div>

                                <div class="route-line"></div>

                                <div class="route-point">
                                    <span class="route-dot route-dot-end"></span>
                                    <span>{{ $ruta->destino->nombre }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="summary-mini-card">
                                    <label class="summary-label">
                                        Fecha
                                    </label>

                                    <p class="summary-value mb-0">
                                        {{ \Carbon\Carbon::parse($datos['fecha'])->format('d M, Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="summary-mini-card">
                                    <label class="summary-label">
                                        Hora
                                    </label>

                                    <p class="summary-value mb-0">
                                        {{ $datos['hora'] ?? 'Pendiente' }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="summary-mini-card d-flex align-items-center justify-content-between">
                                    <div>
                                        <label class="summary-label">
                                            Pasajeros
                                        </label>

                                        <p class="summary-value mb-0">
                                            {{ $datos['pasajeros'] }} pers.
                                        </p>
                                    </div>

                                    <div class="passenger-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-box mb-4">
                            <div class="d-flex gap-3">
                                <div class="info-icon">
                                    <i class="fas fa-circle-info"></i>
                                </div>

                                <p class="small mb-0">
                                    Selecciona un vehículo para continuar y ver el desglose final de tu reserva.
                                </p>
                            </div>
                        </div>

                        <a href="{{ url('/') }}#booking" class="change-route-link">
                            <i class="fas fa-arrow-left me-2"></i>
                            Cambiar ruta
                        </a>
                    </div>
                </div>

                {{-- Vehículos --}}
                <div class="col-lg-8 order-lg-1">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-4">
                        <div>
                            <span class="section-kicker">Selecciona tu unidad</span>
                            <h2 class="h1 fw-black mb-2 text-white">
                                Opciones para tu traslado
                            </h2>
                            <p class="text-soft mb-0">
                                Todas las tarifas muestran el precio total para la ruta seleccionada.
                            </p>
                        </div>

                        <div class="available-pill">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ $ruta->vehiculosDisponibles->count() }} disponibles
                        </div>
                    </div>

                    <div class="row g-4">
                        @forelse($ruta->vehiculosDisponibles as $v)
                            @php
                                $vehicleKey = \Illuminate\Support\Str::lower(\Illuminate\Support\Str::ascii($v->nombre));
                                $vehicleImage = $images[$vehicleKey] ?? asset('images/car_trip.jpg');
                            @endphp

                            <div class="col-12">
                                <div class="vehicle-card">
                                    <div class="row g-0 align-items-stretch">

                                        {{-- Imagen --}}
                                        <div class="col-md-4">
                                            <div class="vehicle-image-box">
                                                <div class="vehicle-glow"></div>

                                                <img src="{{ $vehicleImage }}" alt="{{ $v->nombre }}"
                                                    class="vehicle-image">

                                                <div class="vehicle-capacity-mobile d-md-none">
                                                    <i class="fas fa-users me-1"></i>
                                                    {{ $v->min_pasajeros }}-{{ $v->max_pasajeros }} pers.
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Contenido --}}
                                        <div class="col-md-8">
                                            <div class="vehicle-content">
                                                <div
                                                    class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                                                    <div>
                                                        <span class="vehicle-category">
                                                            Vehículo privado
                                                        </span>

                                                        <h3 class="vehicle-title mb-2">
                                                            {{ $v->nombre }}
                                                        </h3>

                                                        <span class="capacity-badge d-none d-md-inline-flex">
                                                            <i class="fas fa-users me-2"></i>
                                                            Capacidad:
                                                            {{ $v->min_pasajeros }}-{{ $v->max_pasajeros }} pers.
                                                        </span>
                                                    </div>

                                                    <div class="price-box text-md-end">
                                                        <label class="price-label">
                                                            Precio total
                                                        </label>

                                                        <h3 class="price-value mb-0">
                                                            Q{{ number_format($v->pivot->precio_tarifa, 2) }}
                                                        </h3>
                                                    </div>
                                                </div>

                                                <div class="vehicle-features mb-4">
                                                    <div class="feature-item">
                                                        <i class="fas fa-shield-halved"></i>
                                                        <span>Servicio seguro</span>
                                                    </div>

                                                    <div class="feature-item">
                                                        <i class="fas fa-snowflake"></i>
                                                        <span>A/C</span>
                                                    </div>

                                                    <div class="feature-item">
                                                        <i class="fas fa-suitcase-rolling"></i>
                                                        <span>Equipaje</span>
                                                    </div>

                                                    <div class="feature-item">
                                                        <i class="fas fa-user-tie"></i>
                                                        <span>Conductor</span>
                                                    </div>
                                                </div>

                                                <form action="{{ route('reservas.detalles') }}" method="GET">
                                                    <input type="hidden" name="ruta_id" value="{{ $ruta->id }}">
                                                    <input type="hidden" name="vehiculo_id" value="{{ $v->id }}">
                                                    <input type="hidden" name="fecha" value="{{ $datos['fecha'] }}">
                                                    <input type="hidden" name="hora" value="{{ $datos['hora'] }}">
                                                    <input type="hidden" name="pasajeros"
                                                        value="{{ $datos['pasajeros'] }}">
                                                    <input type="hidden" name="precio"
                                                        value="{{ $v->pivot->precio_tarifa }}">
                                                    <input type="hidden" name="id_detalle_ruta"
                                                        value="{{ $v->pivot->id }}">

                                                    <button type="submit" class="select-vehicle-btn">
                                                        Seleccionar vehículo
                                                        <i class="fas fa-arrow-right ms-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-route"></i>
                                    </div>

                                    <h4 class="fw-black text-white mb-3">
                                        No hay vehículos disponibles
                                    </h4>

                                    <p class="text-soft mb-4">
                                        Lo sentimos, no hay vehículos asignados a esta ruta todavía.
                                    </p>

                                    <a href="{{ url('/') }}#booking"
                                        class="select-vehicle-btn d-inline-flex w-auto">
                                        Buscar otra ruta
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        .vehicles-page {
            position: relative;
            overflow: hidden;
            background:
                radial-gradient(circle at 15% 10%, rgba(250, 204, 21, .13), transparent 28%),
                radial-gradient(circle at 85% 20%, rgba(251, 146, 60, .10), transparent 24%),
                linear-gradient(135deg, #020617 0%, #050505 45%, #030712 100%);
            color: #ffffff;
        }

        .vehicles-page::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .025) 1px, transparent 1px);
            background-size: 42px 42px;
            mask-image: linear-gradient(to bottom, black, transparent 85%);
            pointer-events: none;
        }

        .vehicles-page .container {
            position: relative;
            z-index: 1;
        }

        .fw-black {
            font-weight: 900;
        }

        .text-soft {
            color: #94a3b8;
        }

        .eyebrow-badge {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: .55rem .9rem;
            border-radius: 999px;
            background: rgba(250, 204, 21, .10);
            border: 1px solid rgba(250, 204, 21, .28);
            color: #facc15;
            font-size: .75rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        .section-kicker {
            display: inline-block;
            color: #facc15;
            font-size: .72rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .16em;
            margin-bottom: .35rem;
        }

        .trip-summary-card {
            top: 100px;
            padding: 1.5rem;
            border-radius: 1.75rem;
            background: rgba(15, 23, 42, .86);
            border: 1px solid rgba(255, 255, 255, .10);
            box-shadow: 0 24px 70px rgba(0, 0, 0, .35);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .summary-icon,
        .passenger-icon,
        .info-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #020617;
            background: linear-gradient(135deg, #facc15, #fb923c);
        }

        .summary-icon {
            width: 46px;
            height: 46px;
            border-radius: 1rem;
            font-size: 1.1rem;
        }

        .passenger-icon {
            width: 42px;
            height: 42px;
            border-radius: 999px;
        }

        .info-icon {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            font-size: .85rem;
        }

        .summary-label,
        .price-label {
            display: block;
            color: #94a3b8;
            font-size: .66rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: .25rem;
        }

        .summary-value {
            color: #ffffff;
            font-size: 1rem;
            font-weight: 900;
        }

        .summary-mini-card {
            padding: 1rem;
            min-height: 92px;
            border-radius: 1.25rem;
            background: rgba(17, 24, 39, .85);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .route-box {
            padding: 1rem;
            border-radius: 1.25rem;
            background: rgba(17, 24, 39, .85);
            border: 1px solid rgba(255, 255, 255, .08);
        }

        .route-point {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: #ffffff;
            font-weight: 900;
        }

        .route-dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: #facc15;
            box-shadow: 0 0 0 5px rgba(250, 204, 21, .12);
        }

        .route-dot-end {
            background: #fb923c;
            box-shadow: 0 0 0 5px rgba(251, 146, 60, .12);
        }

        .route-line {
            width: 2px;
            height: 30px;
            margin-left: 5px;
            background: linear-gradient(to bottom, #facc15, #fb923c);
            opacity: .75;
        }

        .info-box {
            padding: 1rem;
            border-radius: 1.25rem;
            background: rgba(250, 204, 21, .07);
            border: 1px dashed rgba(250, 204, 21, .28);
            color: #cbd5e1;
        }

        .change-route-link {
            display: inline-flex;
            align-items: center;
            color: #facc15;
            font-size: .85rem;
            font-weight: 900;
            text-decoration: none;
            transition: all .25s ease;
        }

        .change-route-link:hover {
            color: #fde047;
            transform: translateX(-2px);
        }

        .available-pill {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            padding: .7rem 1rem;
            border-radius: 999px;
            color: #bbf7d0;
            background: rgba(34, 197, 94, .10);
            border: 1px solid rgba(34, 197, 94, .22);
            font-size: .8rem;
            font-weight: 900;
            white-space: nowrap;
        }

        .vehicle-card {
            overflow: hidden;
            border-radius: 1.75rem;
            background: rgba(15, 23, 42, .88);
            border: 1px solid rgba(255, 255, 255, .10);
            box-shadow: 0 20px 60px rgba(0, 0, 0, .25);
            transition: all .3s ease;
        }

        .vehicle-card:hover {
            transform: translateY(-5px);
            border-color: rgba(250, 204, 21, .55);
            box-shadow: 0 30px 80px rgba(0, 0, 0, .35), 0 0 45px rgba(250, 204, 21, .08);
        }

        .vehicle-image-box {
            position: relative;
            height: 100%;
            min-height: 245px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 2rem;
            background:
                radial-gradient(circle at center, rgba(250, 204, 21, .13), transparent 45%),
                linear-gradient(135deg, #111827, #020617);
        }

        .vehicle-glow {
            position: absolute;
            width: 150px;
            height: 150px;
            border-radius: 999px;
            background: rgba(250, 204, 21, .18);
            filter: blur(35px);
        }

        .vehicle-image {
            position: relative;
            z-index: 1;
            max-height: 130px;
            max-width: 100%;
            object-fit: contain;
            filter: drop-shadow(0 22px 28px rgba(0, 0, 0, .45));
            transition: transform .35s ease;
        }

        .vehicle-card:hover .vehicle-image {
            transform: scale(1.06) translateY(-2px);
        }

        .vehicle-capacity-mobile {
            position: absolute;
            left: 1rem;
            bottom: 1rem;
            z-index: 2;
            padding: .45rem .75rem;
            border-radius: 999px;
            background: rgba(2, 6, 23, .70);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #facc15;
            font-size: .75rem;
            font-weight: 900;
            backdrop-filter: blur(10px);
        }

        .vehicle-content {
            height: 100%;
            padding: 1.5rem;
        }

        .vehicle-category {
            display: inline-block;
            margin-bottom: .45rem;
            color: #facc15;
            font-size: .68rem;
            font-weight: 900;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .vehicle-title {
            color: #ffffff;
            font-size: 1.6rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .capacity-badge {
            align-items: center;
            width: fit-content;
            padding: .45rem .8rem;
            border-radius: 999px;
            color: #facc15;
            background: rgba(250, 204, 21, .10);
            border: 1px solid rgba(250, 204, 21, .32);
            font-size: .75rem;
            font-weight: 900;
        }

        .price-box {
            min-width: 150px;
        }

        .price-value {
            color: #facc15;
            font-size: 2rem;
            font-weight: 900;
            line-height: 1;
            text-shadow: 0 0 26px rgba(250, 204, 21, .12);
        }

        .vehicle-features {
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
        }

        .feature-item {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .55rem .75rem;
            border-radius: 999px;
            color: #cbd5e1;
            background: rgba(255, 255, 255, .05);
            border: 1px solid rgba(255, 255, 255, .08);
            font-size: .78rem;
            font-weight: 800;
        }

        .feature-item i {
            color: #facc15;
        }

        .select-vehicle-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: auto;
            padding: .85rem 1.5rem;
            border: 0;
            border-radius: 999px;
            color: #020617;
            background: linear-gradient(135deg, #facc15, #fb923c);
            box-shadow: 0 16px 35px rgba(250, 204, 21, .16);
            font-size: .78rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
            text-decoration: none;
            transition: all .25s ease;
        }

        .select-vehicle-btn:hover {
            color: #020617;
            transform: translateY(-1px) scale(1.02);
            box-shadow: 0 20px 45px rgba(250, 204, 21, .25);
            background: linear-gradient(135deg, #fde047, #fb923c);
        }

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            border-radius: 1.75rem;
            background: rgba(15, 23, 42, .88);
            border: 1px solid rgba(255, 255, 255, .10);
        }

        .empty-icon {
            width: 86px;
            height: 86px;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 1.5rem;
            color: #facc15;
            background: rgba(250, 204, 21, .10);
            border: 1px solid rgba(250, 204, 21, .22);
            font-size: 2rem;
        }

        @media (max-width: 767.98px) {
            .vehicles-page {
                padding-top: 2rem !important;
            }

            .trip-summary-card {
                top: 0;
                position: relative !important;
            }

            .vehicle-content {
                padding: 1.25rem;
            }

            .vehicle-title {
                font-size: 1.35rem;
            }

            .price-box {
                text-align: left !important;
            }

            .price-value {
                font-size: 1.75rem;
            }

            .select-vehicle-btn {
                width: 100%;
                padding: .95rem 1.2rem;
            }
        }
    </style>
@endsection

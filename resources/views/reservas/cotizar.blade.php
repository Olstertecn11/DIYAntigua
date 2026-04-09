@extends('layouts.app')

<style>
label{
    color: #ffffff54 !important;
}
</style>

@section('content')
<div class="min-h-screen py-5" style="background-color: #050505; color: white;">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 order-lg-2 mb-4">
                <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top" style="top: 20px; background-color: #0a0a0a; border: 1px solid #262626 !important;">
                    <h5 class="fw-bold mb-4 uppercase " style="font-size: 0.8rem; color: #ffc107;letter-spacing: 1px;">Resumen del Viaje</h5>

                    <div class="mb-4">
                        <label class="d-block uppercase mb-1" style="font-size: 0.65rem; font-weight: 800;">Ruta</label>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-white">{{ $ruta->origen->nombre }}</span>
                            <i class="fas fa-arrow-right text-warning" style="font-size: 0.7rem;"></i>
                            <span class="fw-bold text-white">{{ $ruta->destino->nombre }}</span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <label class=" d-block uppercase mb-1" style="font-size: 0.65rem; font-weight: 800;">Fecha</label>
                            <p class="fw-bold mb-0" style="color: #9e871c">{{ \Carbon\Carbon::parse($datos['fecha'])->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-6">
                            <label class=" d-block uppercase mb-1" style="font-size: 0.65rem; font-weight: 800;">Pasajeros</label>
                            <p class="fw-bold mb-0" style="color: #9e871c">{{ $datos['pasajeros'] }} pers.</p>
                        </div>
                    </div>

                    <hr style="border-color: #262626;">

                    <div class="p-3 rounded-3" style="background-color: #111; border: 1px dashed #262626;">
                        <p class="small  mb-0 italic text-center" style="color: #ffffff87">
                            Selecciona un tipo de vehículo para continuar con tu reserva.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 order-lg-1">
                <div class="mb-5">
                    <h2 class="display-6 fw-bold mb-1 text-white">Vehículos Disponibles</h2>
                    <p class="">Precios calculados en Quetzales (GTQ) para servicio privado.</p>
                </div>

                <div class="row g-4">
                    @php
                        $vehiculos = [
                            [
                                'id' => 'sedan',
                                'nombre' => 'Sedán Privado',
                                'permite' => $ruta->permite_sedan,
                                'precio' => $ruta->precio_sedan,
                                'capacidad' => 'Max. 3 pasajeros',
                                'desc' => 'Ideal para viajes rápidos o ejecutivos.',
                                'icon' => 'https://cdn-icons-png.flaticon.com/512/3202/3202926.png',
                                'color' => '#22c55e'
                            ],
                            [
                                'id' => 'suv',
                                'nombre' => 'SUV Familiar',
                                'permite' => $ruta->permite_suv,
                                'precio' => $ruta->precio_suv,
                                'capacidad' => 'Max. 6 pasajeros',
                                'desc' => 'Espacio extra para maletas y comodidad.',
                                'icon' => 'https://cdn-icons-png.flaticon.com/128/2736/2736781.png',
                                'color' => '#60a5fa'
                            ],
                            [
                                'id' => 'bus',
                                'nombre' => 'Minibus Grupal',
                                'permite' => $ruta->permite_bus,
                                'precio' => $ruta->precio_bus,
                                'capacidad' => 'Max. 15 pasajeros',
                                'desc' => 'La mejor opción para grupos grandes.',
                                'icon' => 'https://cdn-icons-png.flaticon.com/128/16701/16701484.png',
                                'color' => '#c084fc'
                            ]
                        ];
                    @endphp

                    @foreach($vehiculos as $v)
                        @if($v['permite'])
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden"
                                 style="background-color: #0a0a0a; border: 1px solid #262626 !important; transition: transform 0.2s;">
                                <div class="row g-0">
                                    <div class="col-md-3 d-flex align-items-center justify-content-center p-4" style="background-color: #111;">
                                        <img src="{{ $v['icon'] }}" width="80" alt="{{ $v['nombre'] }}" style="filter: brightness(0) invert(1);">
                                    </div>
                                    <div class="col-md-9 p-4">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap">
                                            <div>
                                                <h4 class="fw-bold mb-1 text-white">{{ $v['nombre'] }}</h4>
                                                <p class="text-white small mb-0">{{ $v['desc'] }}</p>
                                                <span class="badge mt-2" style="background-color: #1a1a1a; border: 1px solid #262626; color: {{ $v['color'] }}">
                                                    <i class="fas fa-users me-1"></i> {{ $v['capacidad'] }}
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <p class=" uppercase mb-0" style="font-size: 0.6rem; font-weight: 800;">Total</p>
                                                <h3 class="fw-bold mb-0" style="color: {{ $v['color'] }}">Q{{ number_format($v['precio'], 2) }}</h3>
                                            </div>
                                        </div>

                                        <form action="{{ route('reservas.detalles') }}" method="GET">
                                            <input type="hidden" name="ruta_id" value="{{ $ruta->id }}">
                                            <input type="hidden" name="tipo_vehiculo" value="{{ $v['id'] }}">
                                            <input type="hidden" name="fecha" value="{{ $datos['fecha'] }}">
                                            <input type="hidden" name="hora" value="{{ $datos['hora'] }}">
                                            <input type="hidden" name="pasajeros" value="{{ $datos['pasajeros'] }}">

                                            <button type="submit" class="btn btn-light rounded-pill px-4 mt-3 fw-bold text-xs uppercase shadow-lg">
                                                Seleccionar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach

                    {{-- En caso de que no haya vehículos permitidos --}}
                    @if(!$ruta->permite_sedan && !$ruta->permite_suv && !$ruta->permite_bus)
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x  mb-3"></i>
                            <h4 class="">No hay vehículos disponibles para esta ruta.</h4>
                            <p class=" small">Por favor, contacta a soporte técnico.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card:hover {
        border-color: #404040 !important;
    }
    .btn-light:hover {
        background-color: #e5e5e5;
        transform: translateY(-1px);
    }
</style>
@endsection

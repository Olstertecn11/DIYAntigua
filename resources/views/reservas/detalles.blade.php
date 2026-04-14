@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8" style="background-color: #050505; color: white;">
    <div class="container max-w-6xl">
        <form action="{{ route('reservas.store') }}" method="POST">
            @csrf
            {{-- Datos ocultos para el guardado final --}}
            <input type="hidden" name="ruta_id" value="{{ $ruta->id }}">
            <input type="hidden" name="vehiculo_id" value="{{ $datos['vehiculo_id'] }}">
            <input type="hidden" name="fecha_viaje" value="{{ $datos['fecha'] }}">
            <input type="hidden" name="hora_viaje" value="{{ $datos['hora'] }}">
            <input type="hidden" name="pasajeros" value="{{ $datos['pasajeros'] }}">
            <input type="hidden" name="precio_total" value="{{ $datos['precio'] }}">
            <input type="hidden" name="id_detalle_ruta" value="{{ $datos['id_detalle_ruta'] }}">

            <div class="row">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-4 uppercase tracking-tighter text-white">Detalles del Pasajero</h2>

                    <div class="card border-0 rounded-4 p-4 mb-4" style="background-color: #0a0a0a; border: 1px solid #262626 !important;">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="text-white small uppercase fw-bold mb-2">Nombre Completo</label>
                                <input type="text" name="nombre_cliente" required class="form-control bg-black border-secondary text-white p-3" placeholder="Ej. Juan Pérez">
                            </div>
                            <div class="col-md-6">
                                <label class="text-white small uppercase fw-bold mb-2">Correo Electrónico</label>
                                <input type="email" name="correo_cliente" required class="form-control bg-black border-secondary text-white p-3" placeholder="usuario@gmail.com">
                            </div>
                            <div class="col-md-6">
                                <label class="text-white small uppercase fw-bold mb-2">Teléfono / WhatsApp</label>
                                <input type="tel" name="telefono_cliente" required class="form-control bg-black border-secondary text-white p-3" placeholder="+502 0000-0000">
                            </div>
                        </div>
                    </div>

                    <h2 class="fw-bold mb-4 uppercase tracking-tighter text-white">Puntos de Traslado</h2>
                    <div class="card border-0 rounded-4 p-4" style="background-color: #0a0a0a; border: 1px solid #262626 !important;">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="text-white small uppercase fw-bold mb-2 text-warning">Punto de Recogida (Dirección Exacta)</label>
                                <textarea name="punto_recogida" required class="form-control bg-black border-secondary text-white p-3" rows="2" placeholder="Ej. Puerta 2, Salida de vuelos internacionales..."></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="text-white small uppercase fw-bold mb-2 text-info">Destino Final (Hotel o Dirección)</label>
                                <textarea name="punto_destino" required class="form-control bg-black border-secondary text-white p-3" rows="2" placeholder="Ej. Hotel Casa Santo Domingo, Antigua..."></textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="text-white small uppercase fw-bold mb-2">Notas adicionales o número de vuelo</label>
                                <textarea name="notas_adicionales" class="form-control bg-black border-secondary text-white p-3" rows="2" placeholder="Silla de bebé, maletas extra, etc."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card border-0 rounded-4 p-4 sticky-top shadow-lg" style="top: 20px; background-color: #0a0a0a; border: 1px solid #262626 !important;">
                        <h5 class="fw-bold mb-4 text-white small uppercase">Confirmación Final</h5>

                        <div class="mb-3">
                            <p class="text-white small mb-0 uppercase fw-bold" style="font-size: 0.6rem;">Vehículo Seleccionado</p>
                            @php
                                $vehiculoSeleccionado = $ruta->vehiculosDisponibles->where('id', $datos['vehiculo_id'])->first();
                            @endphp
                            {{-- Usamos Str::upper de Laravel para manejar correctamente la tilde de SEDÁN --}}
                            <p class="h5 fw-bold text-white mb-0">
                                {{ Str::upper($vehiculoSeleccionado->nombre ?? 'Vehículo') }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="text-white small mb-0 uppercase fw-bold" style="font-size: 0.6rem;">Ruta Seleccionada</p>
                            <p class="fw-bold mb-0 text-white">
                                {{ $ruta->origen->nombre }}
                                <i class="fas fa-arrow-right mx-1 text-warning"></i>
                                {{ $ruta->destino->nombre }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="text-white small mb-0 uppercase fw-bold" style="font-size: 0.6rem;">Fecha y Hora</p>
                            <p class="fw-bold mb-0 text-white">
                                {{ \Carbon\Carbon::parse($datos['fecha'])->format('d M, Y') }} - {{ $datos['hora'] }}
                            </p>
                        </div>

                        <hr style="border-color: #262626;">

                        <div class="d-flex justify-content-between align-items-center py-3">
                            <span class="h4 mb-0 fw-bold text-white">TOTAL</span>
                            <span class="h3 mb-0 fw-bold text-success">Q{{ number_format($datos['precio'], 2) }}</span>
                        </div>

                        <button type="submit" class="btn btn-white w-100 py-3 rounded-pill fw-bold uppercase shadow-lg transition-all" style="background-color: white; color: black; border: none;">
                            Confirmar Reserva
                        </button>

                        <p class="text-[#c2c2a4] text-center small mt-3 italic">
                            <i class="fas fa-shield-alt me-1"></i> Reserva segura para DiyAntigua.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .form-control {
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        background-color: #000;
        border-color: #fff;
        color: white;
        box-shadow: none;
    }
    .btn-white {
        background-color: white;
        color: black;
        transition: all 0.3s ease;
    }
    .btn-white:hover {
        background-color: #d4d4d4;
        transform: translateY(-2px);
    }
    .italic {
        font-style: italic;
    }
</style>
@endsection

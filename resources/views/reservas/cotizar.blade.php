@extends('layouts.app')

@section('content')
<div class="min-h-screen py-5" style="background-color: #050505; color: white;">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 order-lg-2 mb-4">
                <div class="card border-0 shadow-lg rounded-4 p-4 sticky-top" style="top: 20px; background-color: #0a0a0a; border: 1px solid #262626 !important;">
                    <h5 class="fw-bold mb-4 uppercase text-warning" style="font-size: 0.8rem; letter-spacing: 1px;">Resumen del Viaje</h5>

                    <div class="mb-4">
                        <label class="text-white d-block uppercase mb-1" style="font-size: 0.65rem; font-weight: 800;">Ruta Seleccionada</label>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-white">{{ $ruta->origen->nombre }}</span>
                            <i class="fas fa-arrow-right text-warning small"></i>
                            <span class="fw-bold text-white">{{ $ruta->destino->nombre }}</span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-6">
                            <label class="text-[#fff] d-block uppercase mb-1" style="font-size: 0.65rem; font-weight: 800;">Fecha</label>
                            <p class="fw-bold mb-0 text-white">{{ \Carbon\Carbon::parse($datos['fecha'])->format('d M, Y') }}</p>
                        </div>
                        <div class="col-6">
                            <label class="text-[#fff] d-block uppercase mb-1" style="font-size: 0.65rem; font-weight: 800;">Pasajeros</label>
                            <p class="fw-bold mb-0 text-white">{{ $datos['pasajeros'] }} pers.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 text-center" style="background-color: #111; border: 1px dashed #262626;">
                        <p class="small mb-0 text-[#848080] italic">Selecciona un vehículo para ver el desglose final.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 order-lg-1">
                <div class="mb-5">
                    <h2 class="display-6 fw-bold mb-1">Vehículos Disponibles</h2>
                    <p class="text-[#fff]">Transporte privado y seguro para la ruta seleccionada.</p>
                </div>

                <div class="row g-4">
                    @forelse($ruta->vehiculosDisponibles as $v)
                    <div class="col-12">
                        <div class="card border-0 rounded-4 overflow-hidden vehicle-card"
                             style="background-color: #0a0a0a; border: 1px solid #262626 !important;">
                            <div class="row g-0">
                                <div class="col-md-3 d-flex align-items-center justify-content-center p-4" style="background-color: #0f0f0f;">
                                    <i class="fas {{ $v->icono ?? 'fa-car' }} fa-4x text-warning opacity-50"></i>
                                </div>

                                <div class="col-md-9 p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h4 class="fw-bold mb-1 text-white uppercase">{{ $v->nombre }}</h4>
                                            <span class="badge rounded-pill" style="background: #1a1a1a; color: #fed94d; border: 1px solid #fed94d4d;">
                                                <i class="fas fa-users me-1"></i> Capacidad: {{ $v->min_pasajeros }}-{{ $v->max_pasajeros }} pers.
                                            </span>
                                        </div>
                                        <div class="text-end">
                                            <label class="text-[#fff] d-block uppercase" style="font-size: 0.6rem; font-weight: 800;">Precio Total</label>
                                            <h3 class="fw-bold mb-0 text-warning">Q{{ number_format($v->pivot->precio_tarifa, 2) }}</h3>
                                        </div>
                                    </div>

                                    <form action="{{ route('reservas.detalles') }}" method="GET">
                                        <input type="hidden" name="ruta_id" value="{{ $ruta->id }}">
                                        <input type="hidden" name="vehiculo_id" value="{{ $v->id }}">
                                        <input type="hidden" name="fecha" value="{{ $datos['fecha'] }}">
                                        <input type="hidden" name="hora" value="{{ $datos['hora'] }}">
                                        <input type="hidden" name="pasajeros" value="{{ $datos['pasajeros'] }}">
                                        <input type="hidden" name="precio" value="{{ $v->pivot->precio_tarifa }}">
                                        <input type="hidden" name="id_detalle_ruta" value="{{ $v->pivot->id }}">

                                        <button type="submit" class="btn btn-warning w-30 w-md-auto rounded-pill px-5 py-2 fw-bold uppercase text-dark shadow-sm">
                                            Seleccionar Vehículo
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <div class="opacity-20 mb-3"><i class="fas fa-route fa-4x"></i></div>
                        <h4 class="text-muted">Lo sentimos, no hay vehículos asignados a esta ruta todavía.</h4>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .vehicle-card {
        transition: all 0.3s ease;
    }
    .vehicle-card:hover {
        transform: translateY(-4px);
        border-color: #fed94d !important;
        box-shadow: 0 10px 30px rgba(254, 217, 77, 0.05) !important;
    }
    .btn-warning {
        background-color: #fed94d;
        border: none;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .btn-warning:hover {
        background-color: #e5c345;
        transform: scale(1.02);
    }
</style>
@endsection

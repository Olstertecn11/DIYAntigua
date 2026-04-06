@extends('layouts.app', ['navbar' => true])

@section('styles')
    <style>
.hero-section {
    min-height: 80vh;
    display: flex;
    align-items: center;
    color: white;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/hero.webp?raw=true') no-repeat;
    background-size: cover;
    background-position: center center;
    transform: scaleX(-1);
    z-index: -1;
}

.hero-section .container {
    position: relative;
    z-index: 1;
}

.booking-card {
    background: white;
    border-radius: 1.5rem;
    color: #333;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.step-card {
    border-radius: 1rem;
    border: none;
    background: #f8f9fa;
    transition: transform 0.3s;
}
.step-card:hover { transform: translateY(-5px); }

.badge-hero{
    background: #fbc9034d;
    color: #322802;
    font-size: 0.8rem;
    border-radius: 0.75rem;
}

.small-container > span{
  padding: 2px 10px;
  backdrop-filter: blur(6px);
  border-radius: 75rem;
  {{-- background: #ffffff2b; --}}
  background: #cd90002b;
}

.form-select{
    font-size: 0.9rem;
}
    </style>
@endsection


@section('content')
<header class="hero-section text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="badge badge-hero  mb-3 px-3 py-2 fw-bold">PREMIUM + FUNCIONAL</span>
                <h1 class="display-3 fw-bold mb-4">Traslados privados y seguros en Guatemala.</h1>
                <p class="lead mb-4">Reserva en minutos tu traslado entre <strong>Guate, Antigua, Panajachel y Quetzaltenango</strong>. Precio claro antes de confirmar.</p>

                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-warning btn-lg px-4 py-3 fw-bold rounded-3">Reservar ahora →</a>
                    <a href="#" class="btn btn-outline-light btn-lg px-4 py-3 fw-bold rounded-3">Consultar reserva</a>
                </div>

                <div class="mt-4 small d-flex flex-wrap gap-3 small-container">
                    <span><i class="bi bi-check2 text-primary"></i> Conductores verificados</span>
                    <span><i class="bi bi-check2 text-primary"></i> Soporte y confirmación</span>
                    <span><i class="bi bi-check2 text-primary"></i> Reserva con o sin cuenta</span>
                </div>
            </div>

            <div class="col-lg-5 offset-lg-1">
                <div class="card booking-card p-4">
                    <h5 class="fw-bold mb-1">Reserva rápida</h5>
                    <p class="text-muted small mb-4">Selecciona ruta y fecha para ver tarifas.</p>

                    <form action="{{ route('home') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-6 text-start">
                                <label class="form-label small fw-bold">Origen</label>
                                <select class="form-select form-select-lg shadow-none" name="origen">
                                    <option>Ciuadad de Guatemala</option>
                                </select>
                            </div>
                            <div class="col-md-6 text-start">
                                <label class="form-label small fw-bold">Destino</label>
                                <select class="form-select form-select-lg shadow-none" name="destino">
                                    <option>Antigua Guatemala</option>
                                </select>
                            </div>
                            <div class="col-md-6 text-start">
                                <label class="form-label small fw-bold">Fecha</label>
                                <input type="date" class="form-control form-control-lg shadow-none">
                            </div>
                            <div class="col-md-3 text-start">
                                <label class="form-label small fw-bold">Hora</label>
                                <input type="time" class="form-control form-control-lg shadow-none">
                            </div>
                            <div class="col-md-3 text-start">
                                <label class="form-label small fw-bold">Pasajeros</label>
                                <input type="number" class="form-control form-control-lg shadow-none" value="2">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning btn-lg w-100 py-3 fw-bold shadow-sm">Ver tarifas</button>
                            </div>
                        </div>
                    </form>
                    <p class="text-muted x-small mt-3 mb-0" style="font-size: 0.7rem;">
                        * Tarifas finales visibles antes de confirmar. Sin cargos ocultos. *
                    </p>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="container py-5 mt-n5">
    <div class="row g-4 text-start">
        <div class="col-md-4">
            <div class="card h-100 p-4 step-card shadow-sm">
                <h6 class="fw-bold text-dark">1) Selecciona tu ruta</h6>
                <p class="text-muted small mb-0">Guate, Antigua, Panajachel o Quetzaltenango. Fecha y hora en segundos.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4 step-card shadow-sm">
                <h6 class="fw-bold text-dark">2) Elige tu vehículo</h6>
                <p class="text-muted small mb-0">Opciones claras por capacidad y comodidad, con precio total visible.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 p-4 step-card shadow-sm">
                <h6 class="fw-bold text-dark">3) Confirma tu reserva</h6>
                <p class="text-muted small mb-0">Con cuenta o como invitado. Recibe código y detalles del servicio.</p>
            </div>
        </div>
    </div>
</section>
@endsection

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
    color: #fed94d;
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
/* Efecto de cristal para secciones oscuras */
.bg-dark-glass {
    background: #0a0a0a;
    position: relative;
}

/* Títulos con degradado dorado */
.text-gradient-gold {
    background: linear-gradient(135deg, #fed94d 0%, #ff8c00 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Tarjetas de flota */
.fleet-card {
    background: #111;
    border: 1px solid #262626;
    transition: all 0.4s ease;
    border-radius: 1.25rem;
}
.fleet-card:hover {
    border-color: #fed94d;
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(254, 217, 77, 0.05);
}

/* Imagen de destino con zoom */
.destination-box {
    position: relative;
    overflow: hidden;
    border-radius: 1rem;
    height: 300px;
}
.destination-box img {
    transition: transform 0.6s ease;
}
.destination-box:hover img {
    transform: scale(1.1);
}
.destination-overlay {
    background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
    position: absolute;
    bottom: 0;
    width: 100%;
    padding: 20px;
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

                    <form action="{{ route('reservas.cotizar') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-6 text-start">
                                <label class="form-label small fw-bold">Origen</label>
                                <select class="form-select form-select-lg shadow-none" name="origen" id="origen-select" required>
                                </select>
                            </div>
                            <div class="col-md-6 text-start">
                                <label class="form-label small fw-bold">Destino</label>
                                <select class="form-select form-select-lg shadow-none" name="destino" id="destino-select" required>
                                </select>
                            </div>
                            <div class="col-md-6 text-start">
                                <label class="form-label small fw-bold">Fecha</label>
                                <input type="date" name="fecha" class="form-control form-control-lg shadow-none" required>
                            </div>
                            <div class="col-md-3 text-start">
                                <label class="form-label small fw-bold">Hora</label>
                                <input type="time" name="hora" class="form-control form-control-lg shadow-none" required>
                            </div>
                            <div class="col-md-3 text-start">
                                <label class="form-label small fw-bold">Pasajeros</label>
                                <input type="number" name="pasajeros" class="form-control form-control-lg shadow-none" value="2" min="1" max="15">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning btn-lg w-100 py-3 fw-bold shadow-sm text-dark">Ver tarifas</button>
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
{{-- seccion de flota --}}
<section class="py-5 bg-dark text-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold uppercase">Nuestra <span class="text-gradient-gold">Flota</span></h2>
            <p class="text-muted">Vehículos de reciente modelo, climatizados y con conductores profesionales.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card fleet-card p-4 h-100">
                    <div class="text-warning mb-3"><i class="fas fa-car-side fa-2x"></i></div>
                    <h4 class="fw-bold">Sedán Ejecutivo</h4>
                    <p class="text-muted small">Ideal para parejas o viajes de negocios. Privacidad absoluta y confort.</p>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-person-check text-warning me-2"></i> 1-3 Pasajeros</li>
                        <li><i class="bi bi-briefcase text-warning me-2"></i> 2 Maletas grandes</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card fleet-card p-4 h-100 border-warning">
                    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3">MÁS POPULAR</span>
                    <div class="text-warning mb-3"><i class="fas fa-shuttle-van fa-2x"></i></div>
                    <h4 class="fw-bold">SUV Familiar</h4>
                    <p class="text-muted small">Perfecto para familias o grupos pequeños que buscan espacio extra.</p>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-person-check text-warning me-2"></i> 4-6 Pasajeros</li>
                        <li><i class="bi bi-briefcase text-warning me-2"></i> 4 Maletas grandes</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card fleet-card p-4 h-100">
                    <div class="text-warning mb-3"><i class="fas fa-bus fa-2x"></i></div>
                    <h4 class="fw-bold">Microbús Grupal</h4>
                    <p class="text-muted small">Transporte de alta capacidad para excursiones o eventos corporativos.</p>
                    <ul class="list-unstyled small">
                        <li><i class="bi bi-person-check text-warning me-2"></i> 7-15 Pasajeros</li>
                        <li><i class="bi bi-briefcase text-warning me-2"></i> Equipaje grupal</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- guatemala publicidad --}}
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row align-items-end mb-5">
            <div class="col-lg-6">
                <h2 class="fw-bold display-6">Explora <span class="text-warning">Guatemala</span></h2>
                <p class="text-muted">Te llevamos a los rincones más mágicos del país con total seguridad.</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <a href="/destinos" class="btn btn-dark rounded-pill px-4">Ver todos los destinos</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="destination-box shadow">
                    <img src="https://images.unsplash.com/photo-1590424600062-1a71d939763c?auto=format&fit=crop&w=400&q=80" class="w-100 h-100 object-fit-cover">
                    <div class="destination-overlay text-white">
                        <h5 class="fw-bold mb-0">Antigua G.</h5>
                        <small class="opacity-75">Desde Q.XXX.00</small>
                    </div>
                </div>
            </div>
            </div>
    </div>
</section>




{{-- social --}}
<section class="py-5 bg-dark-glass text-white border-top border-[#262626]">
    <div class="container py-4">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <h3 class="fw-bold text-warning mb-0">+1,500</h3>
                <p class="small text-muted text-uppercase tracking-widest">Viajes Exitosos</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="fw-bold text-warning mb-0">24/7</h3>
                <p class="small text-muted text-uppercase tracking-widest">Soporte Real</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="fw-bold text-warning mb-0">100%</h3>
                <p class="small text-muted text-uppercase tracking-widest">Precio Fijo</p>
            </div>
            <div class="col-6 col-md-3">
                <h3 class="fw-bold text-warning mb-0">4.9/5</h3>
                <p class="small text-muted text-uppercase tracking-widest">Calificación</p>
            </div>
        </div>
    </div>
</section>

@endsection


<script>

    document.addEventListener('DOMContentLoaded', (event) => {

        const origenSelect = document.getElementById('origen-select');
        const destinoSelect = document.getElementById('destino-select');
        const rutas = @json($rutas);
        rutas.map((item)=>{
            const optionOrigen = document.createElement('option');
            optionOrigen.value = item.origen.id;
            optionOrigen.textContent = item.origen.nombre;
            origenSelect.appendChild(optionOrigen);

            const optionDestino = document.createElement('option');
            optionDestino.value = item.destino.id;
            optionDestino.textContent = item.destino.nombre;
            destinoSelect.appendChild(optionDestino);
        });
    });


</script>

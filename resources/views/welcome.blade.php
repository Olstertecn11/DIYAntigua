@extends('layouts.app', ['navbar' => true])

@section('styles')
    {{--
        Si tu proyecto ya tiene Tailwind instalado con Vite, puedes quitar este CDN
        y dejar solo las clases Tailwind.
    --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        .hero-bg {
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(7, 10, 18, .94), rgba(11, 15, 28, .78), rgba(0, 0, 0, .72)),
                url('https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/hero.webp?raw=true') center center / cover no-repeat;
        }

        .hero-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(250, 204, 21, .24), transparent 28%),
                radial-gradient(circle at 80% 30%, rgba(59, 130, 246, .15), transparent 26%),
                radial-gradient(circle at 50% 90%, rgba(251, 146, 60, .18), transparent 30%);
            pointer-events: none;
        }

        .glass-card {
            background: rgba(255, 255, 255, .92);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, .45);
        }

        .dark-glass {
            background: rgba(15, 23, 42, .76);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, .10);
        }

        .gold-gradient {
            background: linear-gradient(135deg, #facc15, #fb923c);
        }

        .text-gold-gradient {
            background: linear-gradient(135deg, #facc15, #fb923c);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .destination-card img,
        .fleet-card img {
            transition: transform .6s ease;
        }

        .destination-card:hover img,
        .fleet-card:hover img {
            transform: scale(1.08);
        }

        .floating {
            animation: floating 5s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }
    </style>
@endsection

@section('content')
    {{-- HERO --}}
    <header class="hero-bg min-h-screen flex items-center text-white relative">
        <div class="relative z-10 w-full">
            <div class="max-w-7xl mx-auto px-6 py-24 lg:py-32">
                <div class="grid lg:grid-cols-2 gap-12 items-center">

                    <div>

                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-black leading-tight tracking-tight mb-6">
                            Viaja cómodo,
                            <span class="text-gold-gradient">seguro</span>
                            y sin complicaciones.
                        </h1>

                        <p class="text-lg md:text-xl text-slate-200 max-w-2xl leading-relaxed mb-8">
                            Reserva tu traslado privado entre Ciudad de Guatemala, Antigua Guatemala,
                            Panajachel, Quetzaltenango y más destinos. Precio claro antes de confirmar,
                            conductores verificados y atención personalizada.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 mb-10">
                            <a href="#booking"
                                class="inline-flex justify-center items-center rounded-2xl gold-gradient px-7 py-4 font-black text-slate-950 shadow-xl shadow-yellow-500/20 hover:scale-[1.02] transition">
                                Reservar ahora
                                <span class="ml-2">→</span>
                            </a>

                            <a href="#como-funciona"
                                class="inline-flex justify-center items-center rounded-2xl border border-white/20 bg-white/10 px-7 py-4 font-bold text-white hover:bg-white/20 transition">
                                Ver cómo funciona
                            </a>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 max-w-2xl">
                            <div class="dark-glass rounded-2xl p-4">
                                <p class="text-2xl font-black text-yellow-300">24/7</p>
                                <p class="text-sm text-slate-300">Soporte y confirmación</p>
                            </div>
                            <div class="dark-glass rounded-2xl p-4">
                                <p class="text-2xl font-black text-yellow-300">100%</p>
                                <p class="text-sm text-slate-300">Precio visible</p>
                            </div>
                            <div class="dark-glass rounded-2xl p-4">
                                <p class="text-2xl font-black text-yellow-300">+1,500</p>
                                <p class="text-sm text-slate-300">Viajes realizados</p>
                            </div>
                        </div>
                    </div>

                    {{-- FORMULARIO --}}
                    <div id="booking" class="lg:pl-8">
                        <div class="glass-card rounded-[2rem] p-6 md:p-8 shadow-2xl floating">
                            <div class="mb-6">
                                <span
                                    class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-xs font-black uppercase tracking-widest text-yellow-300">
                                    Reserva rápida
                                </span>

                                <h2 class="text-3xl font-black text-slate-950 mt-4 mb-2">
                                    Cotiza tu traslado
                                </h2>

                                <p class="text-slate-500">
                                    Selecciona origen, destino, fecha y número de pasajeros.
                                </p>
                            </div>

                            <form action="{{ route('reservas.cotizar') }}" method="GET" class="space-y-4">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-black text-slate-700 mb-2">
                                            Origen
                                        </label>
                                        <select name="origen" id="origen-select" required
                                            class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100">
                                            <option value="">Selecciona origen</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-black text-slate-700 mb-2">
                                            Destino
                                        </label>
                                        <select name="destino" id="destino-select" required
                                            class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100">
                                            <option value="">Selecciona destino</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-black text-slate-700 mb-2">
                                            Fecha
                                        </label>
                                        <input type="date" name="fecha" id="fecha-reserva" required
                                            class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-black text-slate-700 mb-2">
                                            Hora
                                        </label>
                                        <input type="time" name="hora" required
                                            class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-black text-slate-700 mb-2">
                                            Pasajeros
                                        </label>
                                        <input type="number" name="pasajeros" value="2" min="1" max="15"
                                            class="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100">
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full rounded-2xl gold-gradient px-6 py-3 text-lg font-black text-slate-950 shadow-xl shadow-yellow-500/20 hover:scale-[1.01] transition">
                                    Ver tarifas disponibles
                                </button>
                            </form>

                            <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-3 text-center">
                                <div class="rounded-2xl bg-slate-50 p-3">
                                    <p class="text-xs font-bold text-slate-500">Sin cargos ocultos</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-3">
                                    <p class="text-xs font-bold text-slate-500">Código de reserva</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-3">
                                    <p class="text-xs font-bold text-slate-500">Confirmación rápida</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>

    {{-- MARCAS / CONFIANZA --}}
    <section class="bg-slate-950 text-white py-8 border-y border-white/10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <p class="text-3xl font-black text-yellow-300">4.9/5</p>
                    <p class="text-sm text-slate-400">Calificación promedio</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-yellow-300">+15</p>
                    <p class="text-sm text-slate-400">Rutas disponibles</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-yellow-300">24/7</p>
                    <p class="text-sm text-slate-400">Atención al cliente</p>
                </div>
                <div>
                    <p class="text-3xl font-black text-yellow-300">100%</p>
                    <p class="text-sm text-slate-400">Traslado privado</p>
                </div>
            </div>
        </div>
    </section>

    {{-- BENEFICIOS --}}
    <section id="destinos" class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <span class="text-sm font-black uppercase tracking-widest text-yellow-500">
                    Por qué elegirnos
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-950 mt-3 mb-5">
                    Una experiencia diseñada para viajar tranquilo.
                </h2>
                <p class="text-lg text-slate-500">
                    Ideal para turistas, familias, ejecutivos, grupos pequeños y viajeros que buscan
                    seguridad, puntualidad y una reserva sencilla.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div
                    class="rounded-[2rem] border border-slate-100 bg-slate-50 p-8 hover:-translate-y-2 hover:shadow-2xl transition">
                    <div class="h-14 w-14 rounded-2xl gold-gradient flex items-center justify-center text-2xl mb-6">
                        🛡️
                    </div>
                    <h3 class="text-xl font-black text-slate-950 mb-3">Conductores verificados</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Personal profesional, puntual y con conocimiento de rutas turísticas y zonas urbanas.
                    </p>
                </div>

                <div
                    class="rounded-[2rem] border border-slate-100 bg-slate-50 p-8 hover:-translate-y-2 hover:shadow-2xl transition">
                    <div class="h-14 w-14 rounded-2xl gold-gradient flex items-center justify-center text-2xl mb-6">
                        💳
                    </div>
                    <h3 class="text-xl font-black text-slate-950 mb-3">Precio claro</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Visualiza la tarifa antes de confirmar. Sin cargos sorpresa ni negociación incómoda.
                    </p>
                </div>

                <div
                    class="rounded-[2rem] border border-slate-100 bg-slate-50 p-8 hover:-translate-y-2 hover:shadow-2xl transition">
                    <div class="h-14 w-14 rounded-2xl gold-gradient flex items-center justify-center text-2xl mb-6">
                        📲
                    </div>
                    <h3 class="text-xl font-black text-slate-950 mb-3">Reserva fácil</h3>
                    <p class="text-slate-500 leading-relaxed">
                        Reserva como invitado o con cuenta. Recibe tu código para consultar el estado del servicio.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- COMO FUNCIONA --}}
    <section id="como-funciona" class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-14 items-center">
                <div>
                    <span class="text-sm font-black uppercase tracking-widest text-yellow-500">
                        Proceso simple
                    </span>

                    <h2 class="text-4xl md:text-5xl font-black text-slate-950 mt-3 mb-6">
                        Reserva tu traslado en tres pasos.
                    </h2>

                    <p class="text-lg text-slate-500 mb-8 leading-relaxed">
                        La plataforma está pensada para que cualquier persona pueda cotizar, elegir,
                        confirmar y consultar su reserva sin complicaciones.
                    </p>

                    <div class="space-y-5">
                        <div class="flex gap-4 rounded-3xl bg-white p-5 shadow-sm border border-slate-100">
                            <div
                                class="h-12 w-12 shrink-0 rounded-2xl bg-slate-950 text-yellow-300 flex items-center justify-center font-black">
                                1
                            </div>
                            <div>
                                <h3 class="font-black text-slate-950 mb-1">Selecciona tu ruta</h3>
                                <p class="text-slate-500 text-sm">
                                    Elige origen, destino, fecha, hora y número de pasajeros.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4 rounded-3xl bg-white p-5 shadow-sm border border-slate-100">
                            <div
                                class="h-12 w-12 shrink-0 rounded-2xl bg-slate-950 text-yellow-300 flex items-center justify-center font-black">
                                2
                            </div>
                            <div>
                                <h3 class="font-black text-slate-950 mb-1">Compara opciones</h3>
                                <p class="text-slate-500 text-sm">
                                    Visualiza vehículos, capacidad, comodidad y precio final.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4 rounded-3xl bg-white p-5 shadow-sm border border-slate-100">
                            <div
                                class="h-12 w-12 shrink-0 rounded-2xl bg-slate-950 text-yellow-300 flex items-center justify-center font-black">
                                3
                            </div>
                            <div>
                                <h3 class="font-black text-slate-950 mb-1">Confirma tu reserva</h3>
                                <p class="text-slate-500 text-sm">
                                    Recibe código, detalles del servicio y seguimiento de tu traslado.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -top-6 -left-6 h-32 w-32 rounded-full bg-yellow-300 blur-3xl opacity-40"></div>
                    <div class="absolute -bottom-6 -right-6 h-32 w-32 rounded-full bg-orange-400 blur-3xl opacity-40">
                    </div>

                    <div class="relative rounded-[2rem] overflow-hidden shadow-2xl">
                        <img src="{{ asset('images/car_trip.jpg') }}" alt="Traslado turístico en Guatemala"
                            class="h-[320px] sm:h-[420px] lg:h-[520px] w-full object-cover object-center sm:object-center group-hover:scale-105 transition">

                        <div class="absolute inset-x-6 bottom-6 dark-glass rounded-3xl p-6">
                            <p class="text-yellow-300 font-black text-sm uppercase tracking-widest mb-2">
                                Servicio privado
                            </p>
                            <h3 class="text-2xl font-black text-white mb-2">
                                Del aeropuerto a tu destino sin estrés.
                            </h3>
                            <p class="text-slate-300 text-sm">
                                Perfecto para llegadas, salidas, tours, reuniones o viajes familiares.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FLOTA --}}
    <section id="preguntas" class="bg-slate-950 text-white py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-14">
                <div>
                    <span class="text-sm font-black uppercase tracking-widest text-yellow-300">
                        Nuestra flota
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black mt-3">
                        Vehículos para cada tipo de viaje.
                    </h2>
                </div>

                <p class="text-slate-400 max-w-xl">
                    Unidades cómodas, limpias, climatizadas y adecuadas para traslados individuales,
                    familiares, empresariales o grupales.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div
                    class="fleet-card rounded-[2rem] overflow-hidden bg-white/5 border border-white/10 hover:border-yellow-300/60 transition">
                    <div class="h-56 overflow-hidden">
                        <img src="{{ asset('images/sedan_image.jpg') }}" alt=""
                            class="h-full w-full object-cover">
                    </div>
                    <div class="p-7">
                        <p class="text-yellow-300 text-sm font-black uppercase tracking-widest mb-2">1-3 pasajeros</p>
                        <h3 class="text-2xl font-black mb-3">Sedán Ejecutivo</h3>
                        <p class="text-slate-400 mb-5">
                            Ideal para viajes de negocios, parejas o traslados privados desde el aeropuerto.
                        </p>
                        <ul class="space-y-2 text-sm text-slate-300">
                            <li>✓ Aire acondicionado</li>
                            <li>✓ 2 maletas grandes</li>
                            <li>✓ Servicio privado</li>
                        </ul>
                    </div>
                </div>

                <div
                    class="fleet-card rounded-[2rem] overflow-hidden bg-white/5 border border-yellow-300/60 shadow-2xl shadow-yellow-500/10 transition relative">
                    <div
                        class="absolute top-5 right-5 z-10 rounded-full bg-yellow-300 px-4 py-2 text-xs font-black text-slate-950">
                        MÁS POPULAR
                    </div>
                    <div class="h-56 overflow-hidden">
                        <img src="{{ asset('images/suv_image.jpg') }}" alt="SUV familiar"
                            class="h-full w-full object-cover">
                    </div>
                    <div class="p-7">
                        <p class="text-yellow-300 text-sm font-black uppercase tracking-widest mb-2">4-6 pasajeros</p>
                        <h3 class="text-2xl font-black mb-3">SUV Familiar</h3>
                        <p class="text-slate-400 mb-5">
                            Mayor espacio, comodidad y capacidad para familias o grupos pequeños.
                        </p>
                        <ul class="space-y-2 text-sm text-slate-300">
                            <li>✓ Más espacio interior</li>
                            <li>✓ 4 maletas grandes</li>
                            <li>✓ Recomendado para Antigua y Lago Atitlán</li>
                        </ul>
                    </div>
                </div>

                <div
                    class="fleet-card rounded-[2rem] overflow-hidden bg-white/5 border border-white/10 hover:border-yellow-300/60 transition">
                    <div class="h-56 overflow-hidden">
                        <img src="{{ asset('images/micro_image.jpg') }}" alt="Microbús grupal"
                            class="h-full w-full object-cover">
                    </div>
                    <div class="p-7">
                        <p class="text-yellow-300 text-sm font-black uppercase tracking-widest mb-2">7-15 pasajeros</p>
                        <h3 class="text-2xl font-black mb-3">Microbús Grupal</h3>
                        <p class="text-slate-400 mb-5">
                            Solución cómoda para excursiones, eventos, iglesias, colegios o empresas.
                        </p>
                        <ul class="space-y-2 text-sm text-slate-300">
                            <li>✓ Equipaje grupal</li>
                            <li>✓ Viajes corporativos</li>
                            <li>✓ Tours y eventos</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- DESTINOS --}}
    <section class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-14">
                <div>
                    <span class="text-sm font-black uppercase tracking-widest text-yellow-500">
                        Destinos destacados
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-950 mt-3">
                        Explora Guatemala con comodidad.
                    </h2>
                </div>

                <a href="#booking"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-6 py-4 font-black text-white hover:bg-slate-800 transition">
                    Cotizar destino
                </a>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="destination-card relative h-96 rounded-[2rem] overflow-hidden shadow-xl group">
                    <img src="{{ asset('images/antigua_image.jpg') }}" alt="Antigua Guatemala"
                        class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/30 to-transparent"></div>
                    <div class="absolute bottom-0 p-6 text-white">
                        <h3 class="text-2xl font-black">Antigua Guatemala</h3>
                        <p class="text-sm text-slate-200 mt-2">Ciudad colonial, hoteles, restaurantes y eventos.</p>
                    </div>
                </div>

                <div class="destination-card relative h-96 rounded-[2rem] overflow-hidden shadow-xl group">
                    <img src="{{ asset('images/panajachel_image.jpg') }}" alt="Lago de Atitlán"
                        class="h-full w-full object-cover">

                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/30 to-transparent"></div>
                    <div class="absolute bottom-0 p-6 text-white">
                        <h3 class="text-2xl font-black">Panajachel</h3>
                        <p class="text-sm text-slate-200 mt-2">Traslados al Lago de Atitlán y pueblos cercanos.</p>
                    </div>
                </div>

                <div class="destination-card relative h-96 rounded-[2rem] overflow-hidden shadow-xl group">
                    <img src="{{ asset('images/guatemala_image.jpg') }}" alt="Ciudad de Guatemala"
                        class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/30 to-transparent"></div>
                    <div class="absolute bottom-0 p-6 text-white">
                        <h3 class="text-2xl font-black">Ciudad de Guatemala</h3>
                        <p class="text-sm text-slate-200 mt-2">Aeropuerto, hoteles, zonas empresariales y conexiones.</p>
                    </div>
                </div>

                <div class="destination-card relative h-96 rounded-[2rem] overflow-hidden shadow-xl group">
                    <img src="{{ asset('images/quetzaltenango_image.jpg') }}" alt="Quetzaltenango"
                        class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/30 to-transparent"></div>
                    <div class="absolute bottom-0 p-6 text-white">
                        <h3 class="text-2xl font-black">Quetzaltenango</h3>
                        <p class="text-sm text-slate-200 mt-2">Viajes largos con comodidad y planificación segura.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SEGURIDAD --}}
    <section class="bg-slate-50 py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="rounded-[2rem] overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1494515843206-f3117d3f51b7?auto=format&fit=crop&w=1200&q=80"
                        alt="Viaje seguro" class="h-[520px] w-full object-cover">
                </div>

                <div>
                    <span class="text-sm font-black uppercase tracking-widest text-yellow-500">
                        Seguridad y confianza
                    </span>
                    <h2 class="text-4xl md:text-5xl font-black text-slate-950 mt-3 mb-6">
                        Todo lo necesario para un traslado confiable.
                    </h2>

                    <p class="text-lg text-slate-500 mb-8 leading-relaxed">
                        Nos enfocamos en que el pasajero tenga claridad desde el primer contacto:
                        ruta definida, precio visible, conductor asignado y reserva consultable.
                    </p>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="rounded-3xl bg-white p-6 border border-slate-100 shadow-sm">
                            <h3 class="font-black text-slate-950 mb-2">Confirmación</h3>
                            <p class="text-sm text-slate-500">Recibe los detalles esenciales de tu viaje.</p>
                        </div>

                        <div class="rounded-3xl bg-white p-6 border border-slate-100 shadow-sm">
                            <h3 class="font-black text-slate-950 mb-2">Puntualidad</h3>
                            <p class="text-sm text-slate-500">Planificación según fecha, hora y destino.</p>
                        </div>

                        <div class="rounded-3xl bg-white p-6 border border-slate-100 shadow-sm">
                            <h3 class="font-black text-slate-950 mb-2">Privacidad</h3>
                            <p class="text-sm text-slate-500">Traslado reservado para ti o tu grupo.</p>
                        </div>

                        <div class="rounded-3xl bg-white p-6 border border-slate-100 shadow-sm">
                            <h3 class="font-black text-slate-950 mb-2">Soporte</h3>
                            <p class="text-sm text-slate-500">Acompañamiento antes y durante el servicio.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- TESTIMONIOS --}}
    <section class="bg-white py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-3xl mx-auto text-center mb-14">
                <span class="text-sm font-black uppercase tracking-widest text-yellow-500">
                    Opiniones
                </span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-950 mt-3">
                    Viajeros que confiaron en nosotros.
                </h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="rounded-[2rem] bg-slate-50 border border-slate-100 p-8">
                    <div class="text-yellow-400 text-xl mb-4">★★★★★</div>
                    <p class="text-slate-600 leading-relaxed mb-6">
                        “Servicio puntual, vehículo limpio y conductor muy amable. Perfecto para llegar desde el aeropuerto
                        a Antigua.”
                    </p>
                    <p class="font-black text-slate-950">María G.</p>
                    <p class="text-sm text-slate-400">Traslado aeropuerto</p>
                </div>

                <div class="rounded-[2rem] bg-slate-50 border border-slate-100 p-8">
                    <div class="text-yellow-400 text-xl mb-4">★★★★★</div>
                    <p class="text-slate-600 leading-relaxed mb-6">
                        “La reserva fue rápida y el precio estaba claro desde el inicio. Muy recomendado para familias.”
                    </p>
                    <p class="font-black text-slate-950">Carlos R.</p>
                    <p class="text-sm text-slate-400">Viaje familiar</p>
                </div>

                <div class="rounded-[2rem] bg-slate-50 border border-slate-100 p-8">
                    <div class="text-yellow-400 text-xl mb-4">★★★★★</div>
                    <p class="text-slate-600 leading-relaxed mb-6">
                        “Contratamos microbús para un grupo y todo salió ordenado. Excelente comunicación.”
                    </p>
                    <p class="font-black text-slate-950">Andrea M.</p>
                    <p class="text-sm text-slate-400">Traslado grupal</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="bg-slate-950 text-white py-24">
        <div class="max-w-5xl mx-auto px-6">
            <div class="text-center mb-14">
                <span class="text-sm font-black uppercase tracking-widest text-yellow-300">
                    Preguntas frecuentes
                </span>
                <h2 class="text-4xl md:text-5xl font-black mt-3">
                    Antes de reservar
                </h2>
            </div>

            <div class="space-y-4">
                <details class="group rounded-3xl bg-white/5 border border-white/10 p-6">
                    <summary class="cursor-pointer list-none font-black flex justify-between items-center">
                        ¿El precio es por persona o por vehículo?
                        <span class="text-yellow-300 group-open:rotate-45 transition">+</span>
                    </summary>
                    <p class="text-slate-400 mt-4">
                        El sistema puede mostrar la tarifa final según la ruta, vehículo y cantidad de pasajeros.
                        La idea es que el cliente conozca el precio antes de confirmar.
                    </p>
                </details>

                <details class="group rounded-3xl bg-white/5 border border-white/10 p-6">
                    <summary class="cursor-pointer list-none font-black flex justify-between items-center">
                        ¿Puedo reservar sin crear una cuenta?
                        <span class="text-yellow-300 group-open:rotate-45 transition">+</span>
                    </summary>
                    <p class="text-slate-400 mt-4">
                        Sí. Puedes permitir reservas como invitado y entregar un código para consultar el estado de la
                        reserva.
                    </p>
                </details>

                <details class="group rounded-3xl bg-white/5 border border-white/10 p-6">
                    <summary class="cursor-pointer list-none font-black flex justify-between items-center">
                        ¿Qué pasa si mi vuelo se retrasa?
                        <span class="text-yellow-300 group-open:rotate-45 transition">+</span>
                    </summary>
                    <p class="text-slate-400 mt-4">
                        Puedes incluir seguimiento y soporte para coordinar cambios, especialmente en traslados desde el
                        aeropuerto.
                    </p>
                </details>

                <details class="group rounded-3xl bg-white/5 border border-white/10 p-6">
                    <summary class="cursor-pointer list-none font-black flex justify-between items-center">
                        ¿Tienen traslados grupales?
                        <span class="text-yellow-300 group-open:rotate-45 transition">+</span>
                    </summary>
                    <p class="text-slate-400 mt-4">
                        Sí. La flota puede contemplar SUV, vans o microbuses para grupos, excursiones, colegios, iglesias o
                        empresas.
                    </p>
                </details>
            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <section class="relative overflow-hidden bg-white py-24">
        <div class="absolute inset-0 bg-gradient-to-br from-yellow-50 via-white to-orange-50"></div>

        <div class="relative max-w-5xl mx-auto px-6 text-center">
            <span
                class="inline-flex rounded-full bg-slate-950 px-5 py-2 text-sm font-black uppercase tracking-widest text-yellow-300 mb-6">
                Reserva hoy
            </span>

            <h2 class="text-4xl md:text-6xl font-black text-slate-950 mb-6">
                Tu próximo traslado en Guatemala puede ser más simple.
            </h2>

            <p class="text-lg text-slate-500 max-w-3xl mx-auto mb-10">
                Cotiza tu ruta, elige el vehículo adecuado y confirma tu reserva con una experiencia moderna,
                clara y confiable.
            </p>

            <a href="#booking"
                class="inline-flex items-center justify-center rounded-2xl gold-gradient px-8 py-5 text-lg font-black text-slate-950 shadow-xl shadow-yellow-500/20 hover:scale-[1.02] transition">
                Cotizar mi traslado
                <span class="ml-2">→</span>
            </a>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const origenSelect = document.getElementById('origen-select');
            const destinoSelect = document.getElementById('destino-select');
            const fechaInput = document.getElementById('fecha-reserva');

            const rutas = @json($rutas);

            if (fechaInput) {
                const today = new Date().toISOString().split('T')[0];
                fechaInput.setAttribute('min', today);
            }

            const origenesMap = new Map();

            rutas.forEach((ruta) => {
                if (ruta.origen && !origenesMap.has(ruta.origen.id)) {
                    origenesMap.set(ruta.origen.id, ruta.origen.nombre);
                }
            });

            origenesMap.forEach((nombre, id) => {
                const option = document.createElement('option');
                option.value = id;
                option.textContent = nombre;
                origenSelect.appendChild(option);
            });

            origenSelect.addEventListener('change', (event) => {
                const origenId = String(event.target.value);

                destinoSelect.innerHTML = '<option value="">Selecciona destino</option>';

                if (!origenId) return;

                const destinosMap = new Map();

                rutas.forEach((ruta) => {
                    if (
                        ruta.origen &&
                        ruta.destino &&
                        String(ruta.origen.id) === origenId &&
                        !destinosMap.has(ruta.destino.id)
                    ) {
                        destinosMap.set(ruta.destino.id, ruta.destino.nombre);
                    }
                });

                destinosMap.forEach((nombre, id) => {
                    const option = document.createElement('option');
                    option.value = id;
                    option.textContent = nombre;
                    destinoSelect.appendChild(option);
                });
            });
        });
    </script>
@endsection

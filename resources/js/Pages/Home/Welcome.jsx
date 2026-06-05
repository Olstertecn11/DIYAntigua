import { Head, Link, router } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import { destinations } from '@/Data/destinations';
import { useMemo, useState } from 'react';

const images = {
    hero: '/images/car_trip.jpg',
    trip: '/images/car_trip.jpg',
    sedan: '/images/sedan_image.jpg',
    suv: '/images/suv_image.jpg',
    micro: '/images/micro_image.jpg',
    antigua: '/images/antigua_image.jpg',
    panajachel: '/images/panajachel_image.jpg',
    guatemala: '/images/guatemala_image.jpg',
    quetzaltenango: '/images/quetzaltenango_image.jpg',
};

function uniqueById(items) {
    const map = new Map();

    items.forEach((item) => {
        if (item?.id && !map.has(item.id)) {
            map.set(item.id, item);
        }
    });

    return Array.from(map.values());
}

function GoldButton({ href, children, className = '' }) {
    return (
        <a
            href={href}
            className={`inline-flex items-center justify-center rounded-2xl bg-gradient-to-br from-yellow-300 to-orange-400 px-7 py-4 font-black text-slate-950 no-underline shadow-xl shadow-yellow-500/20 transition hover:scale-[1.02] ${className}`}
        >
            {children}
            <i className="fas fa-arrow-right ml-3 text-sm" />
        </a>
    );
}

function Stat({ value, label, dark = false }) {
    return (
        <div className={dark ? 'rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur' : 'text-center'}>
            <p className={dark ? 'mb-1 text-2xl font-black text-yellow-300' : 'mb-1 text-3xl font-black text-yellow-300'}>
                {value}
            </p>
            <p className={dark ? 'mb-0 text-sm text-slate-300' : 'mb-0 text-sm text-slate-400'}>{label}</p>
        </div>
    );
}

function BenefitCard({ icon, title, text }) {
    return (
        <article className="rounded-[2rem] border border-slate-100 bg-slate-50 p-8 transition hover:-translate-y-2 hover:shadow-2xl">
            <div className="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-yellow-300 to-orange-400 text-xl text-slate-950">
                <i className={`fas ${icon}`} />
            </div>
            <h3 className="mb-3 text-xl font-black text-slate-950">{title}</h3>
            <p className="mb-0 leading-relaxed text-slate-500">{text}</p>
        </article>
    );
}

function ProcessStep({ number, title, text }) {
    return (
        <div className="flex gap-4 rounded-3xl border border-slate-100 bg-white p-5 shadow-sm">
            <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-slate-950 font-black text-yellow-300">
                {number}
            </div>
            <div>
                <h3 className="mb-1 font-black text-slate-950">{title}</h3>
                <p className="mb-0 text-sm text-slate-500">{text}</p>
            </div>
        </div>
    );
}

function FleetCard({ image, label, title, text, items, featured = false }) {
    return (
        <article
            className={`relative overflow-hidden rounded-[2rem] bg-white/5 transition ${
                featured
                    ? 'border border-yellow-300/60 shadow-2xl shadow-yellow-500/10'
                    : 'border border-white/10 hover:border-yellow-300/60'
            }`}
        >
            {featured && (
                <div className="absolute right-5 top-5 z-10 rounded-full bg-yellow-300 px-4 py-2 text-xs font-black text-slate-950">
                    MAS POPULAR
                </div>
            )}
            <div className="h-56 overflow-hidden">
                <img src={image} alt={title} className="h-full w-full object-cover transition duration-700 hover:scale-110" />
            </div>
            <div className="p-7">
                <p className="mb-2 text-sm font-black uppercase tracking-widest text-yellow-300">{label}</p>
                <h3 className="mb-3 text-2xl font-black">{title}</h3>
                <p className="mb-5 text-slate-400">{text}</p>
                <ul className="mb-0 space-y-2 ps-0 text-sm text-slate-300">
                    {items.map((item) => (
                        <li key={item} className="flex gap-2">
                            <i className="fas fa-check mt-1 text-xs text-yellow-300" />
                            <span>{item}</span>
                        </li>
                    ))}
                </ul>
            </div>
        </article>
    );
}

function DestinationCard({ image, title, text, href }) {
    return (
        <Link href={href} className="group relative block h-96 overflow-hidden rounded-[2rem] shadow-xl no-underline">
            <img src={image} alt={title} className="h-full w-full object-cover transition duration-700 group-hover:scale-110" />
            <div className="absolute inset-0 bg-gradient-to-t from-black via-black/30 to-transparent" />
            <div className="absolute bottom-0 p-6 text-white">
                <h3 className="mb-2 text-2xl font-black">{title}</h3>
                <p className="mb-0 text-sm text-slate-200">{text}</p>
            </div>
        </Link>
    );
}

function TrustItem({ title, text }) {
    return (
        <div className="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm">
            <h3 className="mb-2 font-black text-slate-950">{title}</h3>
            <p className="mb-0 text-sm text-slate-500">{text}</p>
        </div>
    );
}

function Testimonial({ quote, name, meta }) {
    return (
        <article className="rounded-[2rem] border border-slate-100 bg-slate-50 p-8">
            <div className="mb-4 text-xl text-yellow-400">★★★★★</div>
            <p className="mb-6 leading-relaxed text-slate-600">"{quote}"</p>
            <p className="mb-1 font-black text-slate-950">{name}</p>
            <p className="mb-0 text-sm text-slate-400">{meta}</p>
        </article>
    );
}

function FaqItem({ question, answer }) {
    return (
        <details className="group rounded-3xl border border-white/10 bg-white/5 p-6">
            <summary className="flex cursor-pointer list-none items-center justify-between gap-4 font-black">
                {question}
                <span className="text-yellow-300 transition group-open:rotate-45">+</span>
            </summary>
            <p className="mb-0 mt-4 text-slate-400">{answer}</p>
        </details>
    );
}

export default function Welcome({ rutas = [], urls = {} }) {
    const [form, setForm] = useState({
        origen: '',
        destino: '',
        fecha: new Date().toISOString().slice(0, 10),
        hora: '',
        pasajeros: 2,
    });

    const origenes = useMemo(() => uniqueById(rutas.map((ruta) => ruta.origen).filter(Boolean)), [rutas]);
    const destinos = useMemo(() => {
        if (!form.origen) {
            return [];
        }

        return uniqueById(
            rutas
                .filter((ruta) => String(ruta.origen?.id) === String(form.origen))
                .map((ruta) => ruta.destino)
                .filter(Boolean),
        );
    }, [form.origen, rutas]);

    const submit = (event) => {
        event.preventDefault();
        router.get(urls.cotizar || '/reservas/cotizar', form);
    };

    return (
        <PublicLayout>
            <Head title="Traslados privados en Guatemala" />

            <main className="min-h-screen bg-white text-slate-950">
                <header id="inicio" className="relative min-h-screen overflow-hidden bg-slate-950 text-white">
                    <div
                        className="absolute inset-0 bg-cover bg-center"
                        style={{
                            backgroundImage: `linear-gradient(135deg, rgba(7, 10, 18, .94), rgba(11, 15, 28, .78), rgba(0, 0, 0, .72)), url('${images.hero}')`,
                        }}
                    />
                    <div className="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(250,204,21,.24),transparent_28%),radial-gradient(circle_at_80%_30%,rgba(59,130,246,.15),transparent_26%),radial-gradient(circle_at_50%_90%,rgba(251,146,60,.18),transparent_30%)]" />

                    <div className="relative z-10 mx-auto grid min-h-screen max-w-7xl items-center gap-12 px-6 pb-16 pt-28 lg:grid-cols-2 lg:pb-20 lg:pt-32">
                        <section>
                            <p className="mb-5 inline-flex rounded-full border border-yellow-300/30 bg-yellow-300/10 px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-yellow-300">
                                Traslados privados en Guatemala
                            </p>
                            <h1 className="mb-6 text-5xl font-black leading-tight tracking-normal md:text-6xl lg:text-7xl">
                                Viaja comodo, <span className="bg-gradient-to-br from-yellow-300 to-orange-400 bg-clip-text text-transparent">seguro</span> y sin complicaciones.
                            </h1>
                            <p className="mb-8 max-w-2xl text-lg leading-relaxed text-slate-200 md:text-xl">
                                Reserva tu traslado privado entre Ciudad de Guatemala, Antigua Guatemala, Panajachel,
                                Quetzaltenango y mas destinos. Precio claro antes de confirmar, conductores verificados
                                y atencion personalizada.
                            </p>
                            <div className="mb-10 flex flex-col gap-4 sm:flex-row">
                                <GoldButton href="#booking">Reservar ahora</GoldButton>
                                <a
                                    href="#nosotros"
                                    className="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-7 py-4 font-bold text-white no-underline transition hover:bg-white/20"
                                >
                                    Ver como funciona
                                </a>
                            </div>
                            <div className="grid max-w-2xl grid-cols-1 gap-3 sm:grid-cols-3">
                                <Stat value="24/7" label="Soporte y confirmacion" dark />
                                <Stat value="100%" label="Precio visible" dark />
                                <Stat value="+1,500" label="Viajes realizados" dark />
                            </div>
                        </section>

                        <section id="booking" className="lg:pl-8">
                            <div className="rounded-[2rem] border border-white/45 bg-white/95 p-6 text-slate-950 shadow-2xl backdrop-blur md:p-8">
                                <div className="mb-6">
                                    <span className="inline-flex rounded-full bg-slate-900 px-4 py-2 text-xs font-black uppercase tracking-widest text-yellow-300">
                                        Reserva rapida
                                    </span>
                                    <h2 className="mb-2 mt-4 text-3xl font-black text-slate-950">Cotiza tu traslado</h2>
                                    <p className="mb-0 text-slate-500">Selecciona origen, destino, fecha y numero de pasajeros.</p>
                                </div>

                                <form onSubmit={submit} className="space-y-4">
                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label className="mb-2 block text-sm font-black text-slate-700">Origen</label>
                                            <select
                                                value={form.origen}
                                                onChange={(event) => setForm((current) => ({ ...current, origen: event.target.value, destino: '' }))}
                                                required
                                                disabled={!origenes.length}
                                                className="w-full rounded-2xl border border-slate-200 bg-white px-3 py-3 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            >
                                                <option value="">{origenes.length ? 'Selecciona origen' : 'No hay rutas disponibles'}</option>
                                                {origenes.map((origen) => (
                                                    <option key={origen.id} value={origen.id}>{origen.nombre}</option>
                                                ))}
                                            </select>
                                        </div>

                                        <div>
                                            <label className="mb-2 block text-sm font-black text-slate-700">Destino</label>
                                            <select
                                                value={form.destino}
                                                onChange={(event) => setForm((current) => ({ ...current, destino: event.target.value }))}
                                                required
                                                disabled={!destinos.length}
                                                className="w-full rounded-2xl border border-slate-200 bg-white px-3 py-3 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            >
                                                <option value="">{destinos.length ? 'Selecciona destino' : 'Elige origen primero'}</option>
                                                {destinos.map((destino) => (
                                                    <option key={destino.id} value={destino.id}>{destino.nombre}</option>
                                                ))}
                                            </select>
                                        </div>
                                    </div>

                                    <div className="grid gap-4 md:grid-cols-3">
                                        <div>
                                            <label className="mb-2 block text-sm font-black text-slate-700">Fecha</label>
                                            <input
                                                type="date"
                                                value={form.fecha}
                                                min={new Date().toISOString().slice(0, 10)}
                                                onChange={(event) => setForm((current) => ({ ...current, fecha: event.target.value }))}
                                                required
                                                className="w-full rounded-2xl border border-slate-200 bg-white px-3 py-3 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            />
                                        </div>
                                        <div>
                                            <label className="mb-2 block text-sm font-black text-slate-700">Hora</label>
                                            <input
                                                type="time"
                                                value={form.hora}
                                                onChange={(event) => setForm((current) => ({ ...current, hora: event.target.value }))}
                                                required
                                                className="w-full rounded-2xl border border-slate-200 bg-white px-3 py-3 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            />
                                        </div>
                                        <div>
                                            <label className="mb-2 block text-sm font-black text-slate-700">Pasajeros</label>
                                            <input
                                                type="number"
                                                value={form.pasajeros}
                                                min="1"
                                                max="15"
                                                onChange={(event) => setForm((current) => ({ ...current, pasajeros: event.target.value }))}
                                                required
                                                className="w-full rounded-2xl border border-slate-200 bg-white px-3 py-3 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            />
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        className="w-full rounded-2xl bg-gradient-to-br from-yellow-300 to-orange-400 px-6 py-3 text-lg font-black text-slate-950 shadow-xl shadow-yellow-500/20 transition hover:scale-[1.01]"
                                    >
                                        Ver tarifas disponibles
                                    </button>
                                </form>

                                <div className="mt-5 grid grid-cols-1 gap-3 text-center sm:grid-cols-3">
                                    {['Sin cargos ocultos', 'Codigo de reserva', 'Confirmacion rapida'].map((label) => (
                                        <div key={label} className="rounded-2xl bg-slate-50 p-3">
                                            <p className="mb-0 text-xs font-bold text-slate-500">{label}</p>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </section>
                    </div>
                </header>

                <section className="border-y border-white/10 bg-slate-950 py-8 text-white">
                    <div className="mx-auto grid max-w-7xl grid-cols-2 gap-6 px-6 text-center md:grid-cols-4">
                        <Stat value="4.9/5" label="Calificacion promedio" />
                        <Stat value="+15" label="Rutas disponibles" />
                        <Stat value="24/7" label="Atencion al cliente" />
                        <Stat value="100%" label="Traslado privado" />
                    </div>
                </section>

                <section className="bg-white py-24">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mx-auto mb-16 max-w-3xl text-center">
                            <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-500">Por que elegirnos</p>
                            <h2 className="mb-5 text-4xl font-black text-slate-950 md:text-5xl">Una experiencia diseñada para viajar tranquilo.</h2>
                            <p className="mb-0 text-lg text-slate-500">
                                Ideal para turistas, familias, ejecutivos, grupos pequeños y viajeros que buscan seguridad,
                                puntualidad y una reserva sencilla.
                            </p>
                        </div>

                        <div className="grid gap-6 md:grid-cols-3">
                            <BenefitCard
                                icon="fa-shield-halved"
                                title="Conductores verificados"
                                text="Personal profesional, puntual y con conocimiento de rutas turisticas y zonas urbanas."
                            />
                            <BenefitCard
                                icon="fa-credit-card"
                                title="Precio claro"
                                text="Visualiza la tarifa antes de confirmar. Sin cargos sorpresa ni negociacion incomoda."
                            />
                            <BenefitCard
                                icon="fa-mobile-screen-button"
                                title="Reserva facil"
                                text="Reserva como invitado o con cuenta. Recibe tu codigo para consultar el estado del servicio."
                            />
                        </div>
                    </div>
                </section>

                <section id="nosotros" className="bg-slate-50 py-24">
                    <div className="mx-auto grid max-w-7xl items-center gap-14 px-6 lg:grid-cols-2">
                        <div>
                            <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-500">Proceso simple</p>
                            <h2 className="mb-6 text-4xl font-black text-slate-950 md:text-5xl">Reserva tu traslado en tres pasos.</h2>
                            <p className="mb-8 text-lg leading-relaxed text-slate-500">
                                La plataforma esta pensada para que cualquier persona pueda cotizar, elegir, confirmar
                                y consultar su reserva sin complicaciones.
                            </p>

                            <div className="space-y-5">
                                <ProcessStep number="1" title="Selecciona tu ruta" text="Elige origen, destino, fecha, hora y numero de pasajeros." />
                                <ProcessStep number="2" title="Compara opciones" text="Visualiza vehiculos, capacidad, comodidad y precio final." />
                                <ProcessStep number="3" title="Confirma tu reserva" text="Recibe codigo, detalles del servicio y seguimiento de tu traslado." />
                            </div>
                        </div>

                        <div className="relative">
                            <div className="relative overflow-hidden rounded-[2rem] shadow-2xl">
                                <img src={images.trip} alt="Traslado turistico en Guatemala" className="h-[340px] w-full object-cover sm:h-[440px] lg:h-[520px]" />
                                <div className="absolute inset-x-6 bottom-6 rounded-3xl border border-white/10 bg-slate-950/75 p-6 text-white backdrop-blur">
                                    <p className="mb-2 text-sm font-black uppercase tracking-widest text-yellow-300">Servicio privado</p>
                                    <h3 className="mb-2 text-2xl font-black">Del aeropuerto a tu destino sin estres.</h3>
                                    <p className="mb-0 text-sm text-slate-300">
                                        Perfecto para llegadas, salidas, tours, reuniones o viajes familiares.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="tours" className="bg-slate-950 py-24 text-white">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mb-14 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-300">Nuestra flota</p>
                                <h2 className="mb-0 text-4xl font-black md:text-5xl">Opciones para cada tipo de viaje.</h2>
                            </div>
                            <p className="mb-0 max-w-xl text-slate-400">
                                Vehiculos privados para traslados ejecutivos, familiares, turismo y grupos.
                            </p>
                        </div>

                        <div className="grid gap-6 md:grid-cols-3">
                            <FleetCard
                                image={images.sedan}
                                label="1-3 pasajeros"
                                title="Sedan Ejecutivo"
                                text="Ideal para viajes de negocios, parejas o traslados privados desde el aeropuerto."
                                items={['Aire acondicionado', '2 maletas grandes', 'Servicio privado']}
                            />
                            <FleetCard
                                image={images.suv}
                                label="4-6 pasajeros"
                                title="SUV Familiar"
                                text="Mayor espacio, comodidad y capacidad para familias o grupos pequeños."
                                items={['Mas espacio interior', '4 maletas grandes', 'Recomendado para Antigua y Lago Atitlan']}
                                featured
                            />
                            <FleetCard
                                image={images.micro}
                                label="7-15 pasajeros"
                                title="Microbus Grupal"
                                text="Solucion comoda para excursiones, eventos, colegios o empresas."
                                items={['Equipaje grupal', 'Viajes corporativos', 'Tours y eventos']}
                            />
                        </div>
                    </div>
                </section>

                <section id="destinos" className="bg-white py-24">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mb-14 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-500">Destinos destacados</p>
                                <h2 className="mb-0 text-4xl font-black text-slate-950 md:text-5xl">Explora Guatemala</h2>
                            </div>
                            <GoldButton href="#booking" className="self-start lg:self-auto">Cotizar destino</GoldButton>
                        </div>

                        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                            {destinations.map((destination) => (
                                <DestinationCard
                                    key={destination.slug}
                                    image={destination.image}
                                    title={destination.title}
                                    text={destination.summary}
                                    href={`/destinos/${destination.slug}`}
                                />
                            ))}
                        </div>
                    </div>
                </section>

                <section className="bg-slate-50 py-24">
                    <div className="mx-auto grid max-w-7xl items-center gap-12 px-6 lg:grid-cols-2">
                        <div className="overflow-hidden rounded-[2rem] shadow-2xl">
                            <img
                                src="https://images.unsplash.com/photo-1494515843206-f3117d3f51b7?auto=format&fit=crop&w=1200&q=80"
                                alt="Viaje seguro"
                                className="h-[420px] w-full object-cover lg:h-[520px]"
                            />
                        </div>

                        <div>
                            <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-500">Seguridad y confianza</p>
                            <h2 className="mb-6 text-4xl font-black text-slate-950 md:text-5xl">Todo lo necesario para un traslado confiable.</h2>
                            <p className="mb-8 text-lg leading-relaxed text-slate-500">
                                Nos enfocamos en que el pasajero tenga claridad desde el primer contacto: ruta definida,
                                precio visible, conductor asignado y reserva consultable.
                            </p>

                            <div className="grid gap-4 sm:grid-cols-2">
                                <TrustItem title="Confirmacion" text="Recibe los detalles esenciales de tu viaje." />
                                <TrustItem title="Puntualidad" text="Planificacion segun fecha, hora y destino." />
                                <TrustItem title="Privacidad" text="Traslado reservado para ti o tu grupo." />
                                <TrustItem title="Soporte" text="Acompañamiento antes y durante el servicio." />
                            </div>
                        </div>
                    </div>
                </section>

                <section className="bg-white py-24">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mx-auto mb-14 max-w-3xl text-center">
                            <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-500">Opiniones</p>
                            <h2 className="mb-0 text-4xl font-black text-slate-950 md:text-5xl">Viajeros que confiaron en nosotros.</h2>
                        </div>

                        <div className="grid gap-6 md:grid-cols-3">
                            <Testimonial
                                quote="Servicio puntual, vehiculo limpio y conductor muy amable. Perfecto para llegar desde el aeropuerto a Antigua."
                                name="Maria G."
                                meta="Traslado aeropuerto"
                            />
                            <Testimonial
                                quote="La reserva fue rapida y el precio estaba claro desde el inicio. Muy recomendado para familias."
                                name="Carlos R."
                                meta="Viaje familiar"
                            />
                            <Testimonial
                                quote="Contratamos microbus para un grupo y todo salio ordenado. Excelente comunicacion."
                                name="Andrea M."
                                meta="Traslado grupal"
                            />
                        </div>
                    </div>
                </section>

                <section id="preguntas" className="bg-slate-950 py-24 text-white">
                    <div className="mx-auto max-w-5xl px-6">
                        <div className="mb-14 text-center">
                            <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-300">Preguntas frecuentes</p>
                            <h2 className="mb-0 text-4xl font-black md:text-5xl">Antes de reservar</h2>
                        </div>

                        <div className="space-y-4">
                            <FaqItem
                                question="El precio es por persona o por vehiculo?"
                                answer="El sistema muestra la tarifa final segun la ruta, vehiculo y cantidad de pasajeros para que conozcas el precio antes de confirmar."
                            />
                            <FaqItem
                                question="Puedo reservar sin crear una cuenta?"
                                answer="Si. Puedes reservar como invitado y recibir un codigo para consultar el estado de tu reserva."
                            />
                            <FaqItem
                                question="Que pasa si mi vuelo se retrasa?"
                                answer="Puedes coordinar cambios con soporte, especialmente en traslados desde el aeropuerto."
                            />
                            <FaqItem
                                question="Tienen traslados grupales?"
                                answer="Si. La flota contempla SUV, vans o microbuses para grupos, excursiones, colegios, iglesias o empresas."
                            />
                        </div>
                    </div>
                </section>

                <section className="relative overflow-hidden bg-white py-24">
                    <div className="absolute inset-0 bg-gradient-to-br from-yellow-50 via-white to-orange-50" />
                    <div className="relative mx-auto max-w-5xl px-6 text-center">
                        <span className="mb-6 inline-flex rounded-full bg-slate-950 px-5 py-2 text-sm font-black uppercase tracking-widest text-yellow-300">
                            Reserva hoy
                        </span>
                        <h2 className="mb-6 text-4xl font-black text-slate-950 md:text-6xl">
                            Tu proximo traslado en Guatemala puede ser mas simple.
                        </h2>
                        <p className="mx-auto mb-10 max-w-3xl text-lg text-slate-500">
                            Cotiza tu ruta, elige el vehiculo adecuado y confirma tu reserva con una experiencia moderna,
                            clara y confiable.
                        </p>
                        <GoldButton href="#booking">Cotizar mi traslado</GoldButton>
                    </div>
                </section>
            </main>
        </PublicLayout>
    );
}

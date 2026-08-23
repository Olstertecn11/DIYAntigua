import { Head, Link, router } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import { destinations } from '@/Data/destinations';
import { publicContact } from '@/Data/contact';
import { useLanguage } from '@/Contexts/LanguageContext';
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
            className={`relative min-w-[82vw] overflow-hidden rounded-[2rem] bg-white/5 transition sm:min-w-[360px] md:min-w-0 ${featured
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
    const { t } = useLanguage();
    const [form, setForm] = useState({
        origen: '',
        destino: '',
        fecha: new Date().toISOString().slice(0, 10),
        hora: '',
        pasajeros: 2,
    });
    const [submitting, setSubmitting] = useState(false);

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

        if (submitting) {
            return;
        }

        setSubmitting(true);
        router.get(urls.cotizar || '/reservas/cotizar', form, {
            onFinish: () => setSubmitting(false),
        });
    };

    return (
        <PublicLayout>
            <Head>
                <title>DYANTIGUA | Traslados Privados y Seguros en Guatemala</title>
                <meta name="description" content="Reserva tu traslado privado en Guatemala de forma fácil y segura. Transporte confiable entre el Aeropuerto, Antigua, Panajachel y más." />

                {/* Datos estructurados fijos para Google */}
                <script type="application/ld+json">
                    {JSON.stringify({
                        "@context": "https://schema.org",
                        "@type": "TaxiService",
                        "name": "DYANTIGUA",
                        "legalName": "DYANTIGUA Traslados Privados",
                        "url": "https://dyantigua.com", // Cambia esto por tu dominio real
                        "logo": "https://dyantigua.com/images/logo.png",
                        "description": "Servicio profesional de transporte privado y traslados ejecutivos y turísticos en el territorio de Guatemala, especializándose en rutas al Aeropuerto La Aurora, Antigua Guatemala y Panajachel.",
                        "provider": {
                            "@type": "LocalBusiness",
                            "name": "DYANTIGUA",
                            "image": "https://dyantigua.com/images/logo.png",
                            "telephone": `+${publicContact.whatsappInternational}`,
                            "email": publicContact.email,
                            "priceRange": "$$",
                            "address": {
                                "@type": "PostalAddress",
                                "addressLocality": "Antigua Guatemala",
                                "addressRegion": "Sacatepéquez",
                                "addressCountry": "GT"
                            }
                        },
                        "areaServed": [
                            { "@type": "Place", "name": "Guatemala" },
                            { "@type": "Place", "name": "Antigua Guatemala" },
                            { "@type": "Place", "name": "Aeropuerto Internacional La Aurora" },
                            { "@type": "Place", "name": "Panajachel" }
                        ],
                        "serviceType": "Private Airport Shuttle & Transfers"
                    })}
                </script>
            </Head>

            <main className="min-h-screen bg-white text-slate-950">
                <header id="inicio" className="relative overflow-hidden bg-slate-950 text-white lg:min-h-[calc(100vh-72px)]">
                    <div
                        className="absolute inset-0 bg-cover bg-center"
                        style={{
                            backgroundImage: `linear-gradient(135deg, rgba(7, 10, 18, .94), rgba(11, 15, 28, .78), rgba(0, 0, 0, .72)), url('${images.hero}')`,
                        }}
                    />
                    <div className="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(250,204,21,.24),transparent_28%),radial-gradient(circle_at_80%_30%,rgba(59,130,246,.15),transparent_26%),radial-gradient(circle_at_50%_90%,rgba(251,146,60,.18),transparent_30%)]" />

                    <div className="relative z-10 mx-auto grid max-w-7xl items-center gap-6 px-6 pb-10 pt-20 lg:min-h-[calc(100vh-72px)] lg:grid-cols-[0.95fr_1.05fr] lg:gap-10 lg:pb-10 lg:pt-24">
                        <section>
                            <p className="mb-3 inline-flex rounded-full border border-yellow-300/30 bg-yellow-300/10 px-4 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-yellow-300 md:mb-4 md:text-xs">
                                {t('Traslados privados en Guatemala')}
                            </p>
                            <h1 className="mb-4 text-3xl font-black leading-tight tracking-normal sm:text-4xl md:mb-5 md:text-5xl xl:text-6xl">
                                {t('Viaja comodo, ')}<span className="bg-gradient-to-br from-yellow-300 to-orange-400 bg-clip-text text-transparent">{t('seguro')}</span>{t(' y sin complicaciones.')}
                            </h1>
                            <p className="mb-5 max-w-2xl text-sm leading-relaxed text-slate-200 sm:text-base md:mb-6 md:text-lg">
                                {t('Reserva tu traslado privado entre Ciudad de Guatemala, Antigua Guatemala, Panajachel, Quetzaltenango y mas destinos. Precio claro antes de confirmar, conductores verificados y atencion personalizada.')}
                            </p>
                            <div className="mb-5 flex flex-col gap-3 sm:mb-7 sm:flex-row">
                                <GoldButton href="#booking">{t('Reservar ahora')}</GoldButton>
                                <a
                                    href="#nosotros"
                                    className="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-7 py-4 font-bold text-white no-underline transition hover:bg-white/20"
                                >
                                    {t('Ver como funciona')}
                                </a>
                            </div>
                        </section>

                        <section id="booking" className="scroll-mt-24 lg:pl-4">
                            <div className="rounded-[1.75rem] border border-white/45 bg-white/95 p-5 text-slate-950 shadow-2xl backdrop-blur md:p-6">
                                <div className="mb-5">
                                    <span className="inline-flex rounded-full bg-slate-900 px-4 py-2 text-xs font-black uppercase tracking-widest text-yellow-300">
                                        {t('Reserva rapida')}
                                    </span>
                                    <h2 className="mb-2 mt-3 text-2xl font-black text-slate-950 md:text-3xl">{t('Cotiza tu traslado')}</h2>
                                    <p className="mb-0 text-slate-500">{t('Selecciona origen, destino, fecha y numero de pasajeros.')}</p>
                                </div>

                                <form onSubmit={submit} className="space-y-3">
                                    <div className="grid gap-3 md:grid-cols-2">
                                        <div>
                                            <label className="mb-2 block text-sm font-black text-slate-700">{t('Origen')}</label>
                                            <select
                                                value={form.origen}
                                                onChange={(event) => setForm((current) => ({ ...current, origen: event.target.value, destino: '' }))}
                                                required
                                                disabled={!origenes.length}
                                                className="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            >
                                                <option value="">{origenes.length ? t('Selecciona origen') : t('No hay rutas disponibles')}</option>
                                                {origenes.map((origen) => (
                                                    <option key={origen.id} value={origen.id}>{origen.nombre}</option>
                                                ))}
                                            </select>
                                        </div>

                                        <div>
                                            <label className="mb-2 block text-sm font-black text-slate-700">{t('Destino')}</label>
                                            <select
                                                value={form.destino}
                                                onChange={(event) => setForm((current) => ({ ...current, destino: event.target.value }))}
                                                required
                                                disabled={!destinos.length}
                                                className="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            >
                                                <option value="">{destinos.length ? t('Selecciona destino') : t('Elige origen primero')}</option>
                                                {destinos.map((destino) => (
                                                    <option key={destino.id} value={destino.id}>{destino.nombre}</option>
                                                ))}
                                            </select>
                                        </div>
                                    </div>

                                    <div className="grid gap-3 md:grid-cols-3">
                                        <div>
                                            <label className="mb-2 block text-sm font-black text-slate-700">{t('Fecha')}</label>
                                            <input
                                                type="date"
                                                value={form.fecha}
                                                min={new Date().toISOString().slice(0, 10)}
                                                onChange={(event) => setForm((current) => ({ ...current, fecha: event.target.value }))}
                                                required
                                                className="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            />
                                        </div>
                                        <div>
                                            <label className="mb-2 block text-sm font-black text-slate-700">{t('Hora')}</label>
                                            <input
                                                type="time"
                                                value={form.hora}
                                                onChange={(event) => setForm((current) => ({ ...current, hora: event.target.value }))}
                                                required
                                                className="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            />
                                        </div>
                                        <div>
                                            <label className="mb-2 block text-sm font-black text-slate-700">{t('Pasajeros')}</label>
                                            <input
                                                type="number"
                                                value={form.pasajeros}
                                                min="1"
                                                max="15"
                                                onChange={(event) => setForm((current) => ({ ...current, pasajeros: event.target.value }))}
                                                required
                                                className="w-full rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-slate-800 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            />
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        disabled={submitting}
                                        className="w-full rounded-2xl bg-gradient-to-br from-yellow-300 to-orange-400 px-6 py-3 text-base font-black text-slate-950 shadow-xl shadow-yellow-500/20 transition hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        {submitting ? t('Buscando tarifas...') : t('Ver tarifas disponibles')}
                                    </button>
                                </form>
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

                <section className="bg-white py-16 md:py-20">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mx-auto mb-12 max-w-3xl text-center">
                            <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-500">{t('Por que elegirnos')}</p>
                            <h2 className="mb-5 text-4xl font-black text-slate-950 md:text-5xl">{t('Una experiencia diseñada para viajar tranquilo.')}</h2>
                            <p className="mb-0 text-lg text-slate-500">
                                {t('Ideal para turistas, familias, ejecutivos, grupos pequeños y viajeros que buscan seguridad, puntualidad y una reserva sencilla.')}
                            </p>
                        </div>

                        <div className="grid gap-6 md:grid-cols-3">
                            <BenefitCard
                                icon="fa-shield-halved"
                                title={t('Conductores verificados')}
                                text={t('Personal profesional, puntual y con conocimiento de rutas turisticas y zonas urbanas.')}
                            />
                            <BenefitCard
                                icon="fa-credit-card"
                                title={t('Precio claro')}
                                text={t('Visualiza la tarifa antes de confirmar. Sin cargos sorpresa ni negociacion incomoda.')}
                            />
                            <BenefitCard
                                icon="fa-mobile-screen-button"
                                title={t('Reserva facil')}
                                text={t('Reserva como invitado o con cuenta. Recibe tu codigo para consultar el estado del servicio.')}
                            />
                        </div>
                    </div>
                </section>

                <section id="nosotros" className="bg-slate-50 py-16 md:py-20">
                    <div className="mx-auto grid max-w-7xl items-center gap-14 px-6 lg:grid-cols-2">
                        <div>
                            <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-500">{t('Proceso simple')}</p>
                            <h2 className="mb-6 text-4xl font-black text-slate-950 md:text-5xl">{t('Reserva tu traslado en tres pasos.')}</h2>
                            <p className="mb-8 text-lg leading-relaxed text-slate-500">
                                {t('La plataforma esta pensada para que cualquier persona pueda cotizar, elegir, confirmar y consultar su reserva sin complicaciones.')}
                            </p>

                            <div className="space-y-5">
                                <ProcessStep number="1" title={t('Elegir')} text={t('Elige ruta, fecha, hora, pasajeros y el vehiculo ideal.')} />
                                <ProcessStep number="2" title={t('Confirmar')} text={t('Confirma datos, verifica tu correo y paga de forma segura.')} />
                                <ProcessStep number="3" title={t('Visualizar en tiempo real tu reserva sin complicaciones')} text={t('Consulta estado, comprobante y cuenta regresiva desde Mis reservas.')} />
                            </div>
                        </div>

                        <div className="relative">
                            <div className="relative overflow-hidden rounded-[2rem] shadow-2xl">
                                <img src={images.trip} alt="Traslado turistico en Guatemala" className="h-[340px] w-full object-cover sm:h-[440px] lg:h-[520px]" />
                                <div className="absolute inset-x-6 bottom-6 rounded-3xl border border-white/10 bg-slate-950/75 p-6 text-white backdrop-blur">
                                    <p className="mb-2 text-sm font-black uppercase tracking-widest text-yellow-300">{t('Servicio privado')}</p>
                                    <h3 className="mb-2 text-2xl font-black">{t('Del aeropuerto a tu destino sin estres.')}</h3>
                                    <p className="mb-0 text-sm text-slate-300">
                                        {t('Perfecto para llegadas, salidas, tours, reuniones o viajes familiares.')}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="tours" className="bg-slate-950 py-16 text-white md:py-20">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mb-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-300">{t('Nuestra flota')}</p>
                                <h2 className="mb-0 text-4xl font-black md:text-5xl">{t('Opciones para cada tipo de viaje.')}</h2>
                            </div>
                            <p className="mb-0 max-w-xl text-slate-400">
                                {t('Vehiculos privados para traslados ejecutivos, familiares, turismo y grupos.')}
                            </p>
                        </div>

                        <div className="-mx-6 overflow-x-auto px-6 pb-3 md:mx-0 md:overflow-visible md:px-0 md:pb-0">
                            <div className="flex gap-5 md:grid md:grid-cols-3 md:gap-6">
                                <FleetCard
                                    image={images.sedan}
                                    label="1-2 pasajeros"
                                    title="Sedan Ejecutivo"
                                    text="Ideal para viajes de negocios, parejas o traslados privados desde el aeropuerto."
                                    items={['Aire acondicionado', '2 maletas grandes', 'Servicio privado']}
                                />
                                <FleetCard
                                    image={images.suv}
                                    label="1-3 pasajeros"
                                    title="SUV Familiar"
                                    text="Mayor espacio, comodidad y capacidad para familias o grupos pequeños."
                                    items={['Mas espacio interior', '4 maletas grandes', 'Recomendado para Antigua y Lago Atitlan']}
                                    featured
                                />
                                <FleetCard
                                    image={images.micro}
                                    label="1-7 pasajeros"
                                    title="Microbus Grupal"
                                    text="Solucion comoda para excursiones, eventos, colegios o empresas."
                                    items={['Equipaje grupal', 'Viajes corporativos', 'Tours y eventos']}
                                />
                            </div>
                        </div>
                    </div>
                </section>

                <section id="destinos" className="bg-white py-16 md:py-20">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mb-10 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-500">{t('Destinos destacados')}</p>
                                <h2 className="mb-0 text-4xl font-black text-slate-950 md:text-5xl">{t('Descubre Guatemala')}</h2>
                            </div>
                            <GoldButton href="#booking" className="self-start lg:self-auto">{t('Cotizar destino')}</GoldButton>
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

                <section className="bg-slate-50 py-16 md:py-20">
                    <div className="mx-auto grid max-w-7xl items-center gap-12 px-6 lg:grid-cols-2">
                        <div className="overflow-hidden rounded-[2rem] shadow-2xl">
                            <img
                                src={images.suv}
                                alt="Traslado privado en SUV"
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

                <section className="bg-white py-16 md:py-20">
                    <div className="mx-auto max-w-7xl px-6">
                        <div className="mx-auto mb-10 max-w-3xl text-center">
                            <p className="mb-3 text-sm font-black uppercase tracking-widest text-yellow-500">Opiniones</p>
                            <h2 className="mb-0 text-4xl font-black text-slate-950 md:text-5xl">Viajeros que confiaron en nosotros.</h2>
                        </div>

                        <div className="grid gap-6 md:grid-cols-3">
                            <Testimonial
                                quote="Reservamos desde el telefono y en minutos ya teniamos precio, codigo y confirmacion. El conductor llego exacto."
                                name="Valeria M."
                                meta="Aeropuerto a Antigua"
                            />
                            <Testimonial
                                quote="Me gusto poder revisar mi reserva despues del pago. Todo se sintio claro, moderno y sin llamadas innecesarias."
                                name="Diego A."
                                meta="Antigua a Ciudad de Guatemala"
                            />
                            <Testimonial
                                quote="Viajamos con equipaje y ninos, la SUV estaba limpia y el precio fue el mismo que vimos al cotizar."
                                name="Sofia L."
                                meta="Viaje familiar a Panajachel"
                            />
                        </div>
                    </div>
                </section>

                <section id="preguntas" className="bg-slate-950 py-16 text-white md:py-20">
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

                <section className="relative overflow-hidden bg-white py-16 md:py-20">
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

import { Link, router, usePage } from '@inertiajs/react';
import { useMemo, useState } from 'react';

function uniqueById(items) {
    const map = new Map();

    items.forEach((item) => {
        if (item?.id && !map.has(item.id)) {
            map.set(item.id, item);
        }
    });

    return Array.from(map.values());
}

function Stat({ value, label }) {
    return (
        <div className="rounded-2xl border border-white/10 bg-white/10 p-4 text-white backdrop-blur">
            <p className="mb-1 text-2xl font-black">{value}</p>
            <p className="mb-0 text-xs font-bold uppercase tracking-widest text-white/60">{label}</p>
        </div>
    );
}

function ServiceCard({ icon, title, text }) {
    return (
        <article className="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div className="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-slate-950 text-yellow-300">
                <i className={`fas ${icon}`} />
            </div>
            <h3 className="mb-2 text-lg font-black text-slate-950">{title}</h3>
            <p className="mb-0 text-sm leading-relaxed text-slate-600">{text}</p>
        </article>
    );
}

export default function Welcome({ rutas = [], urls = {} }) {
    const { props } = usePage();
    const user = props.auth?.user;
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
        <main className="min-h-screen bg-[#f7f6f1] text-slate-950">
            <nav className="fixed inset-x-0 top-0 z-50 border-b border-white/10 bg-black/90 px-4 py-4 text-white backdrop-blur">
                <div className="mx-auto flex max-w-7xl items-center justify-between gap-4">
                    <a href="#inicio" className="flex items-center gap-3 no-underline">
                        <img src="/images/logo.png" alt="DIY Antigua" className="h-10 w-10 object-contain" />
                        <span className="hidden text-sm font-black uppercase tracking-widest text-white sm:block">DIY Antigua</span>
                    </a>
                    <div className="hidden items-center gap-6 text-xs font-black uppercase tracking-widest md:flex">
                        <a href="#booking" className="text-white/75 no-underline hover:text-yellow-300">Reservar</a>
                        <a href="#servicios" className="text-white/75 no-underline hover:text-yellow-300">Servicios</a>
                        <a href="#proceso" className="text-white/75 no-underline hover:text-yellow-300">Proceso</a>
                    </div>
                    <div className="flex items-center gap-2 text-xs font-black uppercase tracking-widest">
                        {user ? (
                            <>
                                <Link href={urls.misReservas || '/mis-reservas'} className="hidden text-white/75 no-underline hover:text-yellow-300 sm:inline">Mis reservas</Link>
                                <Link href={urls.profile || '/perfil'} className="rounded-full bg-yellow-400 px-4 py-2 text-black no-underline">Perfil</Link>
                            </>
                        ) : (
                            <>
                                <Link href={urls.login || '/login'} className="text-white/75 no-underline hover:text-yellow-300">Login</Link>
                                <Link href={urls.register || '/register'} className="rounded-full bg-yellow-400 px-4 py-2 text-black no-underline">Registro</Link>
                            </>
                        )}
                    </div>
                </div>
            </nav>

            <section id="inicio" className="relative overflow-hidden bg-slate-950 px-4 pb-16 pt-28 text-white sm:px-6 lg:pb-24 lg:pt-32">
                <div className="absolute inset-0 bg-[linear-gradient(120deg,rgba(2,6,23,0.92),rgba(15,23,42,0.62)),url('https://images.unsplash.com/photo-1494515843206-f3117d3f51b7?auto=format&fit=crop&w=1800&q=80')] bg-cover bg-center" />
                <div className="relative z-10 mx-auto grid max-w-7xl items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
                    <div>
                        <p className="mb-5 inline-flex rounded-full border border-yellow-300/30 bg-yellow-300/10 px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-yellow-300">
                            Private transfers in Guatemala
                        </p>
                        <h1 className="mb-6 max-w-3xl text-5xl font-black leading-none tracking-tight sm:text-6xl lg:text-7xl">
                            DIY Antigua
                        </h1>
                        <p className="mb-8 max-w-2xl text-lg leading-relaxed text-white/75">
                            Traslados privados desde Antigua, aeropuerto y destinos turisticos con tarifas claras, reserva rapida y seguimiento confiable.
                        </p>
                        <div className="flex flex-col gap-3 sm:flex-row">
                            <a href="#booking" className="inline-flex items-center justify-center rounded-full bg-yellow-400 px-6 py-3 text-sm font-black uppercase tracking-widest text-black no-underline shadow-xl shadow-yellow-500/20">
                                Cotizar traslado
                            </a>
                            <a href="#servicios" className="inline-flex items-center justify-center rounded-full border border-white/20 px-6 py-3 text-sm font-black uppercase tracking-widest text-white no-underline">
                                Ver servicios
                            </a>
                        </div>
                        <div className="mt-10 grid max-w-2xl grid-cols-3 gap-3">
                            <Stat value="24/7" label="Reservas" />
                            <Stat value="15" label="Pasajeros" />
                            <Stat value="GT" label="Rutas" />
                        </div>
                    </div>

                    <div id="booking" className="rounded-[2rem] border border-white/60 bg-white/95 p-6 text-slate-950 shadow-2xl backdrop-blur md:p-8">
                        <span className="inline-flex rounded-full bg-slate-900 px-4 py-2 text-xs font-black uppercase tracking-widest text-yellow-300">
                            Reserva rapida
                        </span>
                        <h2 className="mb-2 mt-4 text-3xl font-black">Cotiza tu traslado</h2>
                        <p className="mb-6 text-slate-500">Selecciona origen, destino, fecha y numero de pasajeros.</p>

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
                                        {origenes.map((origen) => <option key={origen.id} value={origen.id}>{origen.nombre}</option>)}
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
                                        {destinos.map((destino) => <option key={destino.id} value={destino.id}>{destino.nombre}</option>)}
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

                            <button type="submit" className="w-full rounded-2xl bg-yellow-400 px-6 py-3 text-lg font-black text-slate-950 shadow-xl shadow-yellow-500/20 transition hover:bg-yellow-300">
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
                </div>
            </section>

            <section id="servicios" className="px-4 py-16 sm:px-6 lg:py-20">
                <div className="mx-auto max-w-7xl">
                    <div className="mb-10 max-w-2xl">
                        <p className="mb-3 text-xs font-black uppercase tracking-[0.2em] text-yellow-600">Servicios</p>
                        <h2 className="text-3xl font-black sm:text-4xl">Traslados pensados para viajar sin friccion</h2>
                    </div>
                    <div className="grid gap-4 md:grid-cols-3">
                        <ServiceCard icon="fa-plane-arrival" title="Aeropuerto" text="Recepcion y salida hacia Antigua u otros destinos con informacion clara del viaje." />
                        <ServiceCard icon="fa-route" title="Rutas privadas" text="Vehiculos por capacidad, rutas activas y precios definidos desde la cotizacion." />
                        <ServiceCard icon="fa-user-shield" title="Seguimiento" text="Datos de reserva, comprobante y estado del traslado disponibles para el cliente." />
                    </div>
                </div>
            </section>

            <section id="proceso" className="bg-white px-4 py-16 sm:px-6 lg:py-20">
                <div className="mx-auto grid max-w-7xl gap-10 lg:grid-cols-[0.9fr_1.1fr]">
                    <div>
                        <p className="mb-3 text-xs font-black uppercase tracking-[0.2em] text-yellow-600">Proceso</p>
                        <h2 className="mb-4 text-3xl font-black sm:text-4xl">Cotiza, elige vehiculo y confirma.</h2>
                        <p className="text-slate-600">El flujo conserva la logica actual de Laravel para rutas, disponibilidad, validacion, pago y comprobantes.</p>
                    </div>
                    <div className="grid gap-4 md:grid-cols-3">
                        {['Selecciona la ruta', 'Compara tarifas', 'Confirma tu reserva'].map((step, index) => (
                            <div key={step} className="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                                <span className="mb-5 flex h-10 w-10 items-center justify-center rounded-full bg-slate-950 text-sm font-black text-yellow-300">{index + 1}</span>
                                <h3 className="mb-2 text-lg font-black">{step}</h3>
                                <p className="mb-0 text-sm text-slate-600">Informacion directa para avanzar sin pasos innecesarios.</p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            <footer className="bg-black px-4 py-8 text-center text-xs font-bold uppercase tracking-widest text-white/50">
                DIY Antigua Private Transfers
            </footer>
        </main>
    );
}

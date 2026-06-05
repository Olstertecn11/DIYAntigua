import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import { findDestination } from '@/Data/destinations';

export default function Show({ slug }) {
    const destination = findDestination(slug);

    if (!destination) {
        return (
            <PublicLayout>
                <Head title="Destino no encontrado" />
                <main className="bg-slate-50 px-6 pb-20 pt-36">
                    <section className="mx-auto max-w-3xl rounded-[2rem] border border-slate-200 bg-white p-8 text-center shadow-sm">
                        <p className="mb-3 text-sm font-black uppercase tracking-[0.18em] text-yellow-600">Destino</p>
                        <h1 className="mb-4 text-4xl font-black text-slate-950">Destino no encontrado</h1>
                        <p className="mb-8 text-slate-600">El destino que buscas no esta disponible en este momento.</p>
                        <Link href="/#destinos" className="inline-flex rounded-2xl bg-slate-950 px-6 py-4 text-sm font-black uppercase tracking-widest text-white no-underline">
                            Ver destinos
                        </Link>
                    </section>
                </main>
            </PublicLayout>
        );
    }

    return (
        <PublicLayout>
            <Head title={`${destination.title} | Destinos`} />

            <main className="bg-white text-slate-950">
                <section className="relative min-h-[680px] overflow-hidden bg-slate-950 px-6 pb-20 pt-36 text-white">
                    <div
                        className="absolute inset-0 bg-cover bg-center"
                        style={{
                            backgroundImage: `linear-gradient(135deg, rgba(0,0,0,.82), rgba(15,23,42,.68)), url('${destination.image}')`,
                        }}
                    />
                    <div className="relative z-10 mx-auto grid max-w-7xl items-end gap-10 lg:grid-cols-[1.1fr_0.9fr]">
                        <div>
                            <p className="mb-5 inline-flex rounded-full border border-yellow-300/30 bg-yellow-300/10 px-4 py-2 text-xs font-black uppercase tracking-[0.18em] text-yellow-300">
                                {destination.eyebrow}
                            </p>
                            <h1 className="mb-6 text-5xl font-black leading-tight md:text-7xl">{destination.headline}</h1>
                            <p className="mb-8 max-w-3xl text-lg leading-relaxed text-white/75">{destination.copy}</p>
                            <div className="flex flex-col gap-3 sm:flex-row">
                                <Link href="/#booking" className="inline-flex items-center justify-center rounded-2xl bg-[#FCCA00] px-7 py-4 font-black text-black no-underline transition hover:bg-yellow-300">
                                    Reservar traslado
                                    <i className="fas fa-arrow-right ml-3" />
                                </Link>
                                <Link href="/#destinos" className="inline-flex items-center justify-center rounded-2xl border border-white/20 px-7 py-4 font-black text-white no-underline transition hover:bg-white/10">
                                    Ver otros destinos
                                </Link>
                            </div>
                        </div>

                        <aside className="rounded-[2rem] border border-white/10 bg-black/55 p-6 backdrop-blur-xl">
                            <p className="mb-4 text-sm font-black uppercase tracking-[0.18em] text-yellow-300">Ideal para</p>
                            <div className="grid gap-3 sm:grid-cols-2">
                                {destination.highlights.map((highlight) => (
                                    <div key={highlight} className="rounded-2xl bg-white/10 p-4">
                                        <i className="fas fa-check mb-3 text-yellow-300" />
                                        <p className="mb-0 font-black">{highlight}</p>
                                    </div>
                                ))}
                            </div>
                        </aside>
                    </div>
                </section>

                <section className="bg-white py-20">
                    <div className="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-[0.9fr_1.1fr]">
                        <div>
                            <p className="mb-3 text-sm font-black uppercase tracking-[0.18em] text-yellow-600">Rutas frecuentes</p>
                            <h2 className="mb-4 text-4xl font-black">Conecta {destination.title} con los puntos principales.</h2>
                            <p className="mb-0 text-lg leading-relaxed text-slate-600">
                                Puedes cotizar desde el formulario principal para ver vehiculos disponibles, capacidad y precio final antes de confirmar.
                            </p>
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2">
                            {destination.routes.map((route) => (
                                <div key={route} className="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-5">
                                    <p className="mb-2 text-xs font-black uppercase tracking-widest text-slate-500">Ruta</p>
                                    <h3 className="mb-0 text-xl font-black">{route}</h3>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            </main>
        </PublicLayout>
    );
}

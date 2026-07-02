import React from 'react';
import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function DestinationShow({ destination, urls }) {
    const destinationName = destination?.name || 'Tu Destino';

    return (
        <PublicLayout>
            <Head>
                <title>{`Traslados Privados a ${destinationName} | DYANTIGUA`}</title>

                <meta
                    name="description"
                    content={`Viaja seguro y cómodo hacia ${destinationName} con DYANTIGUA. Ofrecemos transporte privado con tarifa fija, conductores profesionales y unidades confortables desde cualquier punto de Guatemala.`}
                />

                <meta
                    name="keywords"
                    content={`transporte a ${destinationName.toLowerCase()}, traslados privados ${destinationName.toLowerCase()}, taxi ${destinationName.toLowerCase()} guatemala, como llegar a ${destinationName.toLowerCase()}, shuttle ${destinationName.toLowerCase()}`}
                />

                <meta property="og:type" content="website" />
                <meta property="og:title" content={`Traslados Privados a ${destinationName} | DYANTIGUA`} />
                <meta property="og:description" content={`Reserva tu viaje privado hacia ${destinationName} desde el Aeropuerto La Aurora o cualquier destino en Guatemala con tarifa fija.`} />
                <meta property="og:image" content="/images/logo.png" />
                <meta property="og:url" content={window.location.href} />
            </Head>

            <main className="min-h-screen bg-[#f5f4ef] pb-14 pt-[72px] text-black">
                {/* Cabecera / Hero del Destino */}
                <section className="relative overflow-hidden border-b border-black/10 bg-[linear-gradient(135deg,#15150f_0%,#050505_58%,#1c1807_100%)] text-white">
                    <div className="pointer-events-none absolute -right-32 -top-40 h-[34rem] w-[34rem] rounded-full bg-[#FCCA00]/15 blur-3xl" />

                    <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                        <Link href={urls?.index || '/'} className="inline-flex items-center rounded-full border border-white/10 bg-white/[0.05] px-4 py-2 text-xs font-black uppercase tracking-widest text-white/70 no-underline transition hover:border-[#FCCA00]/40 hover:text-[#FCCA00]">
                            <i className="fas fa-arrow-left mr-2" />
                            Ver otros destinos
                        </Link>

                        <div className="mt-8 max-w-3xl">
                            <span className="inline-flex rounded-full bg-[#FCCA00] px-4 py-1.5 text-[11px] font-black uppercase tracking-[0.18em] text-black">
                                Destino Destacado
                            </span>
                            <h1 className="mt-5 text-5xl font-black leading-none tracking-tight md:text-6xl">
                                Traslados a <span className="text-[#FCCA00]">{destinationName}</span>
                            </h1>
                            <p className="mt-4 text-base leading-7 text-white/70">
                                {destination?.description || `Disfruta de un viaje sin preocupaciones. Tu transporte privado hacia ${destinationName} incluye chofer profesional, aire acondicionado y monitoreo en tiempo real.`}
                            </p>
                        </div>
                    </div>
                </section>

                {/* Contenido Principal y Formulario / Información del viaje */}
                <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                    <div className="grid gap-8 lg:grid-cols-[1fr_380px]">

                        {/* Detalles e Información del Lugar */}
                        <section className="soft-rise overflow-hidden rounded-[2rem] border border-black/10 bg-white p-6 sm:p-8 shadow-[0_24px_80px_rgba(0,0,0,.04)]">
                            <h2 className="text-2xl font-black text-black mb-4">Información sobre el servicio</h2>
                            <p className="text-sm font-semibold leading-7 text-[#363636] whitespace-pre-wrap">
                                {destination?.extended_info || 'Ofrecemos traslados directos puerta a puerta, adaptados a tus horarios de vuelo o itinerarios personales. Unidades higienizadas y listas para tu viaje de negocios o turismo.'}
                            </p>
                        </section>

                        {/* Tarjeta Lateral de Acción (CTA) */}
                        <aside className="space-y-5">
                            <section className="soft-rise rounded-[2rem] border border-black/10 bg-[#0c0c0a] p-6 text-white shadow-[0_24px_80px_rgba(0,0,0,.14)]">
                                <p className="text-[10px] font-black uppercase tracking-widest text-white/40 mb-2">¿Listo para viajar?</p>
                                <h3 className="text-xl font-black mb-4">Reserva tu traslado a {destinationName}</h3>
                                <div className="grid gap-3">
                                    <Link
                                        href="/reservas/cotizar"
                                        className="rounded-full bg-[#FCCA00] px-5 py-3 text-center text-xs font-black uppercase tracking-widest text-black no-underline shadow-[0_14px_34px_rgba(252,202,0,.28)] transition hover:bg-[#e0b400]"
                                    >
                                        Cotizar Tarifa Exacta
                                    </Link>
                                </div>
                            </section>
                        </aside>

                    </div>
                </div>
            </main>
        </PublicLayout>
    );
}

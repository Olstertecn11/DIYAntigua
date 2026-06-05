import { Link, router } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

const vehicleImages = {
    sedan: '/images/sedan_image.jpg',
    suv: '/images/suv_image.jpg',
    van: '/images/micro_image.jpg',
};

function money(value) {
    return `Q${Number(value || 0).toFixed(2)}`;
}

function vehicleKey(name) {
    return String(name || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

export default function Cotizar({ ruta, datos, images = {}, urls = {} }) {
    const vehiculos = ruta?.vehiculos_disponibles || [];

    const selectVehicle = (vehiculo) => {
        router.get(urls.detalles || '/reservas/detalles', {
            ruta_id: ruta.id,
            vehiculo_id: vehiculo.id,
            fecha: datos.fecha,
            hora: datos.hora,
            pasajeros: datos.pasajeros,
            precio: vehiculo.precio_tarifa,
            id_detalle_ruta: vehiculo.ruta_vehiculo_id,
        });
    };

    return (
        <PublicLayout>
        <main className="min-h-screen bg-[linear-gradient(135deg,#ffffff_0%,#f7f7f2_58%,#ecebe5_100%)] px-4 pb-12 pt-36 text-slate-950 sm:px-6">
            <div className="mx-auto max-w-7xl">
                <Link href={`${urls.home || '/'}#booking`} className="mb-8 inline-flex items-center text-xs font-black uppercase tracking-widest text-slate-700 no-underline">
                    <i className="fas fa-arrow-left mr-2" />
                    Cambiar ruta
                </Link>

                <div className="grid gap-8 lg:grid-cols-[1fr_360px]">
                    <section>
                        <div className="mb-6">
                            <p className="mb-3 text-xs font-black uppercase tracking-[0.2em] text-yellow-600">Selecciona tu unidad</p>
                            <h1 className="mb-2 text-4xl font-black">Vehiculos disponibles</h1>
                            <p className="text-slate-600">Mostramos unidades con capacidad suficiente para {datos.pasajeros} pasajero(s).</p>
                        </div>

                        <div className="space-y-5">
                            {vehiculos.length ? vehiculos.map((vehiculo) => {
                                const key = vehicleKey(vehiculo.nombre);
                                const image = images[key] || vehicleImages[key] || '/images/car_trip.jpg';

                                return (
                                    <article key={vehiculo.id} className="overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                                        <div className="grid md:grid-cols-[260px_1fr]">
                                            <div className="relative min-h-56 overflow-hidden bg-slate-900">
                                                <img src={image} alt={vehiculo.nombre} className="h-full w-full object-cover" />
                                                <span className="absolute bottom-4 left-4 rounded-full bg-black/80 px-3 py-2 text-xs font-black text-white">
                                                    Hasta {vehiculo.max_pasajeros} pers.
                                                </span>
                                            </div>
                                            <div className="p-6">
                                                <div className="mb-5 flex flex-col justify-between gap-4 sm:flex-row">
                                                    <div>
                                                        <p className="mb-2 text-xs font-black uppercase tracking-widest text-yellow-600">Vehiculo privado</p>
                                                        <h2 className="mb-2 text-3xl font-black">{vehiculo.nombre}</h2>
                                                        <span className="inline-flex rounded-full bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700">
                                                            <i className="fas fa-users mr-2" />
                                                            Capacidad: {vehiculo.max_pasajeros}
                                                        </span>
                                                    </div>
                                                    <div className="sm:text-right">
                                                        <p className="mb-1 text-xs font-black uppercase tracking-widest text-slate-500">Precio total</p>
                                                        <p className="mb-0 text-3xl font-black text-slate-950">{money(vehiculo.precio_tarifa)}</p>
                                                    </div>
                                                </div>

                                                <div className="mb-5 grid grid-cols-2 gap-3 text-sm font-bold text-slate-600 sm:grid-cols-4">
                                                    {['Seguro', 'A/C', 'Equipaje', 'Conductor'].map((feature) => (
                                                        <span key={feature} className="rounded-xl bg-slate-50 px-3 py-2">
                                                            <i className="fas fa-check mr-2 text-yellow-600" />
                                                            {feature}
                                                        </span>
                                                    ))}
                                                </div>

                                                <button
                                                    type="button"
                                                    onClick={() => selectVehicle(vehiculo)}
                                                    className="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black uppercase tracking-widest text-white transition hover:bg-slate-800 sm:w-auto"
                                                >
                                                    Seleccionar vehiculo
                                                    <i className="fas fa-arrow-right ml-2" />
                                                </button>
                                            </div>
                                        </div>
                                    </article>
                                );
                            }) : (
                                <div className="rounded-[1.75rem] border border-slate-200 bg-white p-8 text-center shadow-sm">
                                    <i className="fas fa-route mb-4 text-3xl text-yellow-600" />
                                    <h2 className="mb-3 text-2xl font-black">No hay vehiculos con capacidad suficiente</h2>
                                    <p className="mb-6 text-slate-600">Cambia la cantidad de pasajeros o consulta otra ruta disponible.</p>
                                    <Link href={`${urls.home || '/'}#booking`} className="inline-flex rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black uppercase tracking-widest text-white no-underline">
                                        Buscar otra ruta
                                    </Link>
                                </div>
                            )}
                        </div>
                    </section>

                    <aside className="h-fit rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-6">
                        <div className="mb-5 flex items-center justify-between">
                            <div>
                                <p className="mb-1 text-xs font-black uppercase tracking-widest text-yellow-600">Resumen</p>
                                <h2 className="mb-0 text-xl font-black">Resumen del viaje</h2>
                            </div>
                            <span className="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-950 text-yellow-300">
                                <i className="fas fa-route" />
                            </span>
                        </div>
                        <div className="mb-5 rounded-2xl bg-slate-50 p-4">
                            <p className="text-sm font-black">{ruta?.origen?.nombre}</p>
                            <div className="my-3 h-px bg-slate-200" />
                            <p className="mb-0 text-sm font-black">{ruta?.destino?.nombre}</p>
                        </div>
                        <div className="grid grid-cols-2 gap-3">
                            <div className="rounded-2xl bg-slate-50 p-4">
                                <p className="mb-1 text-xs font-black uppercase tracking-widest text-slate-500">Fecha</p>
                                <p className="mb-0 font-black">{datos.fecha}</p>
                            </div>
                            <div className="rounded-2xl bg-slate-50 p-4">
                                <p className="mb-1 text-xs font-black uppercase tracking-widest text-slate-500">Hora</p>
                                <p className="mb-0 font-black">{datos.hora}</p>
                            </div>
                            <div className="col-span-2 rounded-2xl bg-slate-50 p-4">
                                <p className="mb-1 text-xs font-black uppercase tracking-widest text-slate-500">Pasajeros</p>
                                <p className="mb-0 font-black">{datos.pasajeros} pers.</p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </main>
        </PublicLayout>
    );
}

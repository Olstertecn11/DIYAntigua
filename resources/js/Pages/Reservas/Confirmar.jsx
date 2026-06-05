import { Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import { useEffect, useState } from 'react';

function money(value) {
    return `Q${Number(value || 0).toFixed(2)}`;
}

export default function Confirmar({ reserva, urls = {} }) {
    const [remaining, setRemaining] = useState(reserva.estado_viaje === 'cancelado' ? 'Cancelada' : 'Calculando...');
    const isPaid = reserva.estado_pago === 'pagado';

    useEffect(() => {
        if (reserva.estado_viaje === 'cancelado') {
            return undefined;
        }

        const update = () => {
            const diff = new Date(reserva.travel_at_iso).getTime() - Date.now();

            if (diff <= 0) {
                setRemaining('En curso');
                return;
            }

            const days = Math.floor(diff / 86400000);
            const hours = Math.floor((diff % 86400000) / 3600000);
            const minutes = Math.floor((diff % 3600000) / 60000);
            setRemaining(`${days}d ${hours}h ${minutes}m`);
        };

        update();
        const timer = window.setInterval(update, 60000);
        return () => window.clearInterval(timer);
    }, [reserva.estado_viaje, reserva.travel_at_iso]);

    return (
        <PublicLayout>
        <main className="min-h-screen bg-[linear-gradient(90deg,#FCCA00_0_12px,transparent_12px),linear-gradient(135deg,#ffffff_0%,#f7f7f2_58%,#ecebe5_100%)] px-4 pb-12 pt-36 text-slate-950 sm:px-6">
            <div className="mx-auto max-w-6xl">
                <Link href={urls.home || '/'} className="inline-flex text-xs font-black uppercase tracking-widest text-slate-700 no-underline">
                    <i className="fas fa-arrow-left mr-2" />
                    Inicio
                </Link>

                <section className="mt-6 flex flex-col justify-between gap-6 rounded-[1.75rem] border border-slate-200 bg-white/95 p-8 shadow-sm lg:flex-row lg:items-center">
                    <div>
                        <p className="mb-2 text-xs font-black uppercase tracking-[0.2em] text-yellow-600">Comprobante de reserva</p>
                        <h1 className="mb-3 text-5xl font-black tracking-tight">{reserva.codigo_reserva}</h1>
                        <p className="mb-0 text-lg font-bold text-slate-600">{reserva.ruta.origen} {'->'} {reserva.ruta.destino}</p>
                    </div>
                    <div className="min-w-56 rounded-2xl bg-black p-5 text-center text-white">
                        <span className="text-xs font-black uppercase tracking-widest text-white/50">Tiempo restante</span>
                        <strong className="my-2 block text-3xl font-black text-yellow-300">{remaining}</strong>
                        <small className="text-white/60">{reserva.fecha} {reserva.hora}</small>
                    </div>
                </section>

                <div className="mt-6 grid gap-6 lg:grid-cols-[1fr_360px]">
                    <section className="rounded-[1.75rem] border border-slate-200 bg-white/95 p-6 shadow-sm">
                        <div className="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                            <div>
                                <p className="mb-2 text-xs font-black uppercase tracking-[0.2em] text-yellow-600">Traslado privado</p>
                                <h2 className="mb-0 text-2xl font-black">Detalles del viaje</h2>
                            </div>
                            <span className={isPaid ? 'rounded-full bg-green-100 px-4 py-2 text-xs font-black uppercase tracking-widest text-green-700' : 'rounded-full bg-yellow-100 px-4 py-2 text-xs font-black uppercase tracking-widest text-yellow-700'}>
                                Pago: {String(reserva.estado_pago).replace('_', ' ')}
                            </span>
                        </div>

                        <div className="mb-6 grid gap-4 rounded-2xl bg-slate-50 p-5 sm:grid-cols-[1fr_auto_1fr] sm:items-center">
                            <div>
                                <span className="text-xs font-black uppercase tracking-widest text-slate-500">Origen</span>
                                <strong className="block text-lg">{reserva.ruta.origen || 'Pendiente'}</strong>
                            </div>
                            <i className="fas fa-van-shuttle text-2xl text-yellow-600" />
                            <div className="sm:text-right">
                                <span className="text-xs font-black uppercase tracking-widest text-slate-500">Destino</span>
                                <strong className="block text-lg">{reserva.ruta.destino || 'Pendiente'}</strong>
                            </div>
                        </div>

                        <div className="grid gap-3 md:grid-cols-2">
                            {[
                                ['Pasajero', reserva.nombre_cliente],
                                ['Telefono', reserva.telefono_cliente],
                                ['Correo', reserva.correo_cliente],
                                ['Fecha', reserva.fecha],
                                ['Hora', reserva.hora],
                                ['Vehiculo', reserva.tipo_vehiculo],
                                ['Pasajeros', reserva.pasajeros],
                                ['Total', money(reserva.precio_total)],
                            ].map(([label, value]) => (
                                <div key={label} className="rounded-2xl bg-slate-50 p-4">
                                    <span className="text-xs font-black uppercase tracking-widest text-slate-500">{label}</span>
                                    <strong className="mt-1 block break-words">{value}</strong>
                                </div>
                            ))}
                        </div>

                        <div className="mt-5 rounded-2xl bg-slate-50 p-4">
                            <span className="text-xs font-black uppercase tracking-widest text-slate-500">Puntos y notas</span>
                            <p className="mb-0 mt-2 whitespace-pre-line text-sm font-semibold text-slate-700">{reserva.notas_adicionales || 'Sin notas adicionales.'}</p>
                        </div>
                    </section>

                    <aside className="h-fit rounded-[1.75rem] border border-slate-200 bg-white/95 p-6 shadow-sm">
                        <h2 className="mb-5 text-2xl font-black">Estado y comprobante</h2>
                        <div className="mb-5 rounded-2xl bg-slate-50 p-5 text-center">
                            <img src={reserva.qr_url} alt="QR de reserva" className="mx-auto mb-3 h-40 w-40" />
                            <span className="text-xs font-black uppercase tracking-widest text-slate-500">Validacion digital</span>
                            <strong className="block">{reserva.codigo_reserva}</strong>
                        </div>
                        <div className="mb-5 space-y-2 text-sm font-bold text-slate-600">
                            <p className="mb-0">Pago: {String(reserva.estado_pago).replace('_', ' ')}</p>
                            <p className="mb-0">Viaje: {reserva.estado_viaje}</p>
                            <p className="mb-0">Reserva: {reserva.created_at}</p>
                        </div>
                        {reserva.transaction && (
                            <div className="mb-5 rounded-2xl border border-slate-200 p-4">
                                <span className="text-xs font-black uppercase tracking-widest text-slate-500">Transaccion</span>
                                <strong className="block break-words">{reserva.transaction.provider_transaction_id || reserva.transaction.reference}</strong>
                                <small>{String(reserva.transaction.card_brand || 'CARD').toUpperCase()} **** {reserva.transaction.card_last_four || '----'}</small>
                            </div>
                        )}
                        <div className={isPaid ? 'mb-5 rounded-2xl bg-green-50 p-4 text-green-800' : 'mb-5 rounded-2xl bg-yellow-50 p-4 text-yellow-800'}>
                            <strong>{isPaid ? 'Pago confirmado' : 'Pago pendiente'}</strong>
                            <p className="mb-0 mt-1 text-sm">{isPaid ? 'Tu traslado esta pagado y listo para coordinacion.' : 'Puedes completar el pago para dejar tu reserva confirmada.'}</p>
                        </div>
                        <div className="space-y-3">
                            <a href={urls.pdf} className="block rounded-2xl bg-slate-950 px-5 py-3 text-center text-sm font-black uppercase tracking-widest text-white no-underline">Descargar comprobante</a>
                            {!isPaid && <a href={urls.checkout} className="block rounded-2xl bg-yellow-400 px-5 py-3 text-center text-sm font-black uppercase tracking-widest text-black no-underline">Pagar reserva</a>}
                            <Link href={`${urls.home || '/'}#booking`} className="block rounded-2xl border border-slate-200 px-5 py-3 text-center text-sm font-black uppercase tracking-widest text-slate-700 no-underline">Nueva cotizacion</Link>
                        </div>
                    </aside>
                </div>
            </div>
        </main>
        </PublicLayout>
    );
}

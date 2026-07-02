import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import PublicLayout from '@/Layouts/PublicLayout';
import ReservationCountdown from '@/Components/Reservations/ReservationCountdown';

function money(value) {
    return `Q${Number(value || 0).toLocaleString('es-GT', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function human(value) {
    return String(value || '').replaceAll('_', ' ');
}

export default function UserReservationShow({ reserva, urls }) {
    const [openCancel, setOpenCancel] = useState(false);
    const [motivo, setMotivo] = useState('');

    const cancel = (event) => {
        event.preventDefault();
        router.post(urls.cancel, { motivo_cancelacion: motivo }, { preserveScroll: true, onSuccess: () => setOpenCancel(false) });
    };

    const [origin, destination] = String(reserva.ruta.label || '').split(' -> ');

    return (
        <PublicLayout>
            <Head>
                <title>{`Reservación ${reserva.codigo_reserva} | DYANTIGUA`}</title>
                <meta name="robots" content="noindex, nofollow" />
                <meta name="googlebot" content="noindex, nofollow" />
            </Head>
            <main className="min-h-screen bg-[radial-gradient(circle_at_85%_10%,rgba(252,202,0,.13),transparent_28%),#f5f4ef] pb-14 pt-[72px] text-black">
                <section className="relative overflow-hidden border-b border-black/10 bg-[linear-gradient(135deg,#15150f_0%,#050505_58%,#1c1807_100%)] text-white">
                    <div className="pointer-events-none absolute -right-32 -top-40 h-[34rem] w-[34rem] rounded-full bg-[#FCCA00]/15 blur-3xl" />
                    <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                        <Link href={urls.index} className="inline-flex items-center rounded-full border border-white/10 bg-white/[0.05] px-4 py-2 text-xs font-black uppercase tracking-widest text-white/70 no-underline transition hover:border-[#FCCA00]/40 hover:text-[#FCCA00]">
                            <i className="fas fa-arrow-left mr-2" />
                            Mis reservaciones
                        </Link>

                        <div className="soft-rise mt-7 grid gap-8 lg:grid-cols-[1fr_360px] lg:items-end">
                            <div>
                                <span className="inline-flex rounded-full bg-[#FCCA00] px-4 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-black">
                                    {reserva.codigo_reserva}
                                </span>
                                <h1 className="mt-5 max-w-4xl text-5xl font-black leading-none tracking-tight md:text-6xl">
                                    {origin || 'Origen'} <span className="text-[#FCCA00]">→</span> {destination || 'Destino'}
                                </h1>
                                <p className="mt-4 max-w-2xl text-base leading-7 text-white/62">
                                    Tu comprobante digital reúne ruta, pago, políticas de cancelación y referencia de transacción.
                                </p>
                            </div>
                            <ReservationCountdown travelIso={reserva.travel_iso} travelAt={reserva.travel_at} status={reserva.estado_viaje} dark compact />
                        </div>
                    </div>
                </section>

                <div className="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[1fr_380px] lg:px-8">
                    <section className="soft-rise overflow-hidden rounded-[2rem] border border-black/10 bg-white shadow-[0_24px_80px_rgba(0,0,0,.08)]">
                        <div className="border-b border-black/10 bg-white p-6 sm:p-7">
                            <div className="grid gap-5 md:grid-cols-[1fr_auto_1fr] md:items-center">
                                <RouteStop label="Origen" value={origin || 'Origen'} />
                                <span className="hidden h-16 w-16 items-center justify-center rounded-3xl bg-black text-2xl text-[#FCCA00] md:flex">
                                    <i className="fas fa-van-shuttle" />
                                </span>
                                <RouteStop label="Destino" value={destination || reserva.ruta.label || 'Destino'} align="right" />
                            </div>
                        </div>

                        <div className="grid gap-0 md:grid-cols-2">
                            <InfoBlock label="Pasajero" value={reserva.nombre_cliente} icon="fa-user" />
                            <InfoBlock label="Correo" value={reserva.correo_cliente} icon="fa-envelope" />
                            <InfoBlock label="Telefono" value={reserva.telefono_cliente} icon="fa-phone" />
                            <InfoBlock label="Fecha" value={reserva.travel_date} icon="fa-calendar-day" />
                            <InfoBlock label="Hora" value={reserva.travel_time} icon="fa-clock" />
                            <InfoBlock label="Vehiculo" value={String(reserva.tipo_vehiculo || '').toUpperCase()} icon="fa-car-side" />
                            <InfoBlock label="Pasajeros" value={reserva.pasajeros} icon="fa-users" />
                            <InfoBlock label="Total" value={money(reserva.precio_total)} icon="fa-receipt" strong />
                        </div>

                        <div className="border-t border-black/10 bg-[#fbfaf6] p-6 sm:p-7">
                            <p className="mb-3 text-[10px] font-black uppercase tracking-widest text-[#777]">Notas y puntos</p>
                            <p className="mb-0 whitespace-pre-wrap rounded-2xl border border-black/10 bg-white p-5 text-sm font-semibold leading-7 text-[#363636]">{reserva.notas_adicionales || 'Sin notas adicionales.'}</p>
                        </div>
                    </section>

                    <aside className="space-y-5">
                        <section className="soft-rise rounded-[2rem] border border-black/10 bg-[#0c0c0a] p-6 text-white shadow-[0_24px_80px_rgba(0,0,0,.14)]">
                            <p className="mb-4 text-[10px] font-black uppercase tracking-widest text-white/40">Estado</p>
                            <div className="grid gap-3">
                                <Status label="Pago" value={human(reserva.estado_pago)} paid={reserva.estado_pago === 'pagado'} />
                                <Status label="Viaje" value={human(reserva.estado_viaje)} />
                                <Status label="Reembolso" value={human(reserva.reembolso_estado || 'sin solicitud')} />
                            </div>
                            {reserva.transaction && (
                                <div className="mt-5 rounded-3xl border border-white/10 bg-white/[0.06] p-5">
                                    <p className="mb-2 text-[10px] font-black uppercase tracking-widest text-white/40">Referencia</p>
                                    <strong className="block break-all text-sm text-[#FCCA00]">{reserva.transaction.reference}</strong>
                                    <small className="text-white/55">{String(reserva.transaction.card_brand || 'CARD').toUpperCase()} **** {reserva.transaction.card_last_four || '----'}</small>
                                </div>
                            )}
                        </section>

                        <section className="soft-rise rounded-[2rem] border border-black/10 bg-white p-6 shadow-[0_24px_80px_rgba(0,0,0,.08)]">
                            <div className={`rounded-3xl p-5 ${reserva.can_cancel ? 'bg-green-100 text-green-900' : 'bg-red-100 text-red-900'}`}>
                                <i className={`fas ${reserva.can_cancel ? 'fa-shield-halved' : 'fa-lock'} mb-3 text-xl`} />
                                <strong className="block text-lg font-black">{reserva.can_cancel ? 'Cancelable con reembolso' : 'Cancelacion cerrada'}</strong>
                                <p className="mb-0 mt-1 text-sm font-bold">{reserva.can_cancel ? 'Puedes cancelar porque faltan mas de 24 horas para el viaje.' : 'Ya no cumple la politica de mas de 24 horas antes del viaje.'}</p>
                            </div>
                            <div className="mt-5 grid gap-3">
                                <a href={urls.pdf} className="rounded-full bg-[#FCCA00] px-5 py-3 text-center text-xs font-black uppercase tracking-widest text-black no-underline shadow-[0_14px_34px_rgba(252,202,0,.28)]">Descargar comprobante</a>
                                {reserva.can_cancel && <button type="button" onClick={() => setOpenCancel(true)} className="rounded-full border-0 bg-red-600 px-5 py-3 text-xs font-black uppercase tracking-widest text-white">Cancelar reservacion</button>}
                            </div>
                        </section>
                    </aside>
                </div>
            </main>

            {openCancel && (
                <div className="fixed inset-0 z-[120] flex items-center justify-center bg-black/75 p-4 backdrop-blur">
                    <div className="soft-rise w-full max-w-xl rounded-[2rem] bg-white p-7 shadow-2xl">
                        <h2 className="text-2xl font-black text-black">Cancelar reservacion</h2>
                        <p className="text-[#666]">La reserva se cancelara y el reembolso quedara en revision administrativa si aplica.</p>
                        <form onSubmit={cancel} className="mt-4">
                            <textarea value={motivo} onChange={(event) => setMotivo(event.target.value)} maxLength="500" rows="3" placeholder="Motivo opcional" className="w-full resize-y rounded-2xl border border-black/10 p-3 outline-none focus:border-[#FCCA00] focus:ring-4 focus:ring-[#FCCA00]/20" />
                            <div className="mt-4 grid grid-cols-2 gap-3">
                                <button type="button" onClick={() => setOpenCancel(false)} className="rounded-full border border-black/10 bg-white px-5 py-3 text-xs font-black uppercase">Volver</button>
                                <button className="rounded-full border-0 bg-red-600 px-5 py-3 text-xs font-black uppercase text-white">Confirmar cancelacion</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </PublicLayout>
    );
}

function RouteStop({ label, value, align = 'left' }) {
    return (
        <div className={align === 'right' ? 'min-w-0 md:text-right' : 'min-w-0'}>
            <p className="mb-2 text-[10px] font-black uppercase tracking-widest text-[#777]">{label}</p>
            <h2 className="mb-0 break-words text-3xl font-black leading-tight">{value}</h2>
        </div>
    );
}

function InfoBlock({ label, value, icon, strong = false }) {
    return (
        <div className="border-b border-black/10 p-6 md:border-r md:even:border-r-0">
            <p className="mb-2 text-[10px] font-black uppercase tracking-widest text-[#777]"><i className={`fas ${icon} mr-2 text-[#b89500]`} />{label}</p>
            <strong className={`block break-words ${strong ? 'text-2xl text-black' : 'text-sm text-[#1b1b1b]'}`}>{value || 'N/A'}</strong>
        </div>
    );
}

function Status({ label, value, paid = false }) {
    return (
        <div className="flex items-center justify-between gap-4 rounded-2xl border border-white/10 bg-white/[0.06] px-4 py-3">
            <span className="text-[10px] font-black uppercase tracking-widest text-white/40">{label}</span>
            <strong className={`text-right text-xs font-black uppercase ${paid ? 'text-green-300' : 'text-[#FCCA00]'}`}>{value}</strong>
        </div>
    );
}

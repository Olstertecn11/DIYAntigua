import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

function money(value) {
    return `Q${Number(value || 0).toLocaleString('es-GT', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

export default function UserReservationsIndex({ reservas, urls }) {
    return (
        <PublicLayout>
            <Head title="Mis reservaciones" />
            <main className="min-h-screen bg-[#f7f7f2] py-10">
                <div className="mx-auto max-w-6xl px-4">
                    <div className="mb-8">
                        <span className="inline-flex items-center rounded-full border border-[#FCCA00]/40 bg-[#FCCA00]/20 px-4 py-2 text-xs font-black uppercase tracking-widest text-[#363636]"><i className="fas fa-suitcase-rolling mr-2" />Mi cuenta</span>
                        <h1 className="mt-3 text-4xl font-black text-black">Mis reservaciones</h1>
                        <p className="mb-0 text-lg text-[#666]">Historial de traslados, pagos, comprobantes y solicitudes de cancelacion.</p>
                    </div>
                    <div className="grid gap-4">
                        {reservas.data.length > 0 ? reservas.data.map((reserva) => <ReservationCard key={reserva.id} reserva={reserva} />) : (
                            <div className="rounded-[28px] border border-black/10 bg-white px-6 py-16 text-center">
                                <i className="fas fa-calendar-check mb-5 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#FCCA00] text-2xl text-black" />
                                <h2 className="font-black text-black">Aun no tienes reservaciones</h2>
                                <p className="mb-6 text-[#666]">Cuando reserves con tu cuenta, tus traslados apareceran aqui.</p>
                                <Link href={`${urls.home}#booking`} className="inline-flex rounded-full bg-[#FCCA00] px-5 py-3 text-xs font-black uppercase tracking-widest text-black no-underline">Reservar traslado</Link>
                            </div>
                        )}
                    </div>
                    <Pagination links={reservas.links} />
                </div>
            </main>
        </PublicLayout>
    );
}

function ReservationCard({ reserva }) {
    return (
        <article className="grid items-center gap-5 rounded-3xl border border-black/10 bg-white p-6 shadow-[0_24px_70px_rgba(0,0,0,.08)] lg:grid-cols-[1fr_auto_auto]">
            <div>
                <span className="text-xs font-black uppercase tracking-[.14em] text-[#b89500]">{reserva.codigo_reserva}</span>
                <h2 className="my-2 text-2xl font-black text-black">{reserva.ruta.label}</h2>
                <p className="m-0 font-bold text-[#666]">{reserva.travel_at} · {String(reserva.tipo_vehiculo || '').toUpperCase()} · {reserva.pasajeros} pasajero(s)</p>
            </div>
            <div className="flex flex-col gap-2 lg:items-end">
                <span className="rounded-full bg-[#f6f6f3] px-3 py-2 text-xs font-black uppercase text-[#363636]">{reserva.estado_pago}</span>
                <span className="rounded-full bg-[#f6f6f3] px-3 py-2 text-xs font-black uppercase text-[#363636]">{reserva.estado_viaje}</span>
                <strong className="text-2xl font-black text-black">{money(reserva.precio_total)}</strong>
            </div>
            <div className="flex flex-col gap-2 lg:items-end">
                <span className={`rounded-full px-3 py-2 text-xs font-black uppercase ${reserva.can_cancel ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}>{reserva.can_cancel ? 'Cancelable con reembolso' : 'Fuera de politica 24h'}</span>
                <Link href={reserva.urls.show} className="inline-flex justify-center rounded-full bg-[#FCCA00] px-5 py-3 text-xs font-black uppercase tracking-widest text-black no-underline">Ver reserva</Link>
            </div>
        </article>
    );
}

function Pagination({ links }) {
    if (!links?.length) return null;
    return <div className="mt-6 flex flex-wrap gap-2">{links.map((link, index) => <Link key={`${link.label}-${index}`} href={link.url || '#'} preserveScroll className={`rounded-lg border px-3 py-2 text-xs font-bold ${link.active ? 'border-[#FCCA00] bg-[#FCCA00] text-black' : 'border-black/10 bg-white text-black/70'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`} dangerouslySetInnerHTML={{ __html: link.label }} />)}</div>;
}

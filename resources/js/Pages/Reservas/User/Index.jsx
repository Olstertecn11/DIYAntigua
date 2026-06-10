import { Head, Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

function money(value) {
    return `Q${Number(value || 0).toLocaleString('es-GT', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function human(value) {
    return String(value || '').replaceAll('_', ' ');
}

export default function UserReservationsIndex({ reservas, urls }) {
    const items = reservas.data || [];
    const paidCount = items.filter((reserva) => reserva.estado_pago === 'pagado').length;
    const upcomingCount = items.filter((reserva) => reserva.estado_viaje === 'programado').length;
    const refundableCount = items.filter((reserva) => reserva.can_cancel).length;
    const total = items.reduce((sum, reserva) => sum + Number(reserva.precio_total || 0), 0);

    return (
        <PublicLayout>
            <Head title="Mis reservaciones" />
            <main className="min-h-screen bg-[#f5f4ef] pb-14 pt-32 text-black">
                <section className="border-b border-black/10 bg-[linear-gradient(90deg,#FCCA00_0_12px,transparent_12px),linear-gradient(135deg,#10100e_0%,#050505_64%,#16130a_100%)] text-white">
                    <div className="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1fr_420px] lg:px-8">
                        <div className="soft-rise">
                            <span className="inline-flex items-center gap-2 rounded-full border border-[#FCCA00]/35 bg-[#FCCA00]/10 px-4 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-[#FCCA00]">
                                <i className="fas fa-suitcase-rolling" />
                                Mi cuenta
                            </span>
                            <h1 className="mt-5 max-w-3xl text-5xl font-black tracking-tight md:text-6xl">Mis reservaciones</h1>
                            <p className="mt-4 max-w-2xl text-base leading-7 text-white/62">
                                Revisa tus traslados, descarga comprobantes y gestiona cancelaciones sin perder el hilo del viaje.
                            </p>
                        </div>
                        <div className="soft-rise grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                            <HeroMetric label="Valor reservado" value={money(total)} icon="fa-receipt" />
                            <HeroMetric label="Reservas visibles" value={items.length} icon="fa-layer-group" />
                        </div>
                    </div>
                </section>

                <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div className="mb-6 grid gap-4 md:grid-cols-3">
                        <Summary label="Programadas" value={upcomingCount} icon="fa-calendar-check" />
                        <Summary label="Pagadas" value={paidCount} icon="fa-credit-card" />
                        <Summary label="Cancelables" value={refundableCount} icon="fa-shield-halved" />
                    </div>

                    <div className="grid gap-5">
                        {items.length > 0 ? items.map((reserva) => <ReservationCard key={reserva.id} reserva={reserva} />) : (
                            <div className="soft-rise rounded-[2rem] border border-black/10 bg-white px-6 py-16 text-center shadow-[0_24px_70px_rgba(0,0,0,.08)]">
                                <i className="fas fa-calendar-check mb-5 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#FCCA00] text-2xl text-black" />
                                <h2 className="text-2xl font-black text-black">Aun no tienes reservaciones</h2>
                                <p className="mb-6 mt-2 text-[#666]">Cuando reserves con tu cuenta, tus traslados apareceran aqui.</p>
                                <Link href={`${urls.home}#booking`} className="inline-flex rounded-full bg-[#FCCA00] px-6 py-3 text-xs font-black uppercase tracking-widest text-black no-underline shadow-[0_16px_36px_rgba(252,202,0,.28)]">Reservar traslado</Link>
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
    const paid = reserva.estado_pago === 'pagado';

    return (
        <article className="soft-rise group overflow-hidden rounded-[2rem] border border-black/10 bg-white shadow-[0_24px_70px_rgba(0,0,0,.08)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_34px_90px_rgba(0,0,0,.14)]">
            <div className="grid lg:grid-cols-[1fr_260px]">
                <div className="p-6 sm:p-7">
                    <div className="mb-5 flex flex-wrap items-center gap-3">
                        <span className="inline-flex items-center gap-2 rounded-full bg-[#FCCA00]/20 px-3 py-1.5 text-xs font-black uppercase tracking-[.14em] text-[#7b6200]">
                            <i className="fas fa-qrcode" />
                            {reserva.codigo_reserva}
                        </span>
                        <Badge tone={paid ? 'green' : 'yellow'}>{human(reserva.estado_pago)}</Badge>
                        <Badge>{human(reserva.estado_viaje)}</Badge>
                    </div>

                    <div className="grid gap-5 md:grid-cols-[1fr_auto_1fr] md:items-center">
                        <Place label="Origen" value={reserva.ruta.label?.split(' -> ')[0] || 'Origen'} />
                        <div className="hidden h-14 w-14 items-center justify-center rounded-2xl bg-black text-[#FCCA00] md:flex">
                            <i className="fas fa-van-shuttle" />
                        </div>
                        <Place label="Destino" value={reserva.ruta.label?.split(' -> ')[1] || reserva.ruta.label || 'Destino'} align="right" />
                    </div>

                    <div className="mt-6 grid gap-3 sm:grid-cols-3">
                        <Mini label="Fecha" value={reserva.travel_at} icon="fa-clock" />
                        <Mini label="Vehiculo" value={String(reserva.tipo_vehiculo || '').toUpperCase()} icon="fa-car-side" />
                        <Mini label="Pasajeros" value={`${reserva.pasajeros} pasajero(s)`} icon="fa-users" />
                    </div>
                </div>

                <div className="border-t border-black/10 bg-[#0c0c0a] p-6 text-white lg:border-l lg:border-t-0">
                    <div className="flex h-full flex-col justify-between gap-5">
                        <div>
                            <p className="mb-1 text-[10px] font-black uppercase tracking-widest text-white/40">Total</p>
                            <strong className="text-4xl font-black text-[#FCCA00]">{money(reserva.precio_total)}</strong>
                            <div className={`mt-4 rounded-2xl p-4 text-sm font-black ${reserva.can_cancel ? 'bg-green-400/12 text-green-200' : 'bg-red-400/12 text-red-200'}`}>
                                <i className={`fas ${reserva.can_cancel ? 'fa-shield-halved' : 'fa-lock'} mr-2`} />
                                {reserva.can_cancel ? 'Cancelable con reembolso' : 'Fuera de politica 24h'}
                            </div>
                        </div>
                        <Link href={reserva.urls.show} className="inline-flex items-center justify-center rounded-full bg-[#FCCA00] px-5 py-3 text-xs font-black uppercase tracking-widest text-black no-underline transition hover:bg-yellow-300">
                            Ver reserva
                            <i className="fas fa-arrow-right ml-2" />
                        </Link>
                    </div>
                </div>
            </div>
        </article>
    );
}

function HeroMetric({ label, value, icon }) {
    return (
        <div className="rounded-3xl border border-white/10 bg-white/[0.06] p-5 backdrop-blur">
            <div className="flex items-center justify-between gap-4">
                <span className="text-[10px] font-black uppercase tracking-widest text-white/42">{label}</span>
                <i className={`fas ${icon} text-[#FCCA00]`} />
            </div>
            <strong className="mt-2 block text-3xl font-black">{value}</strong>
        </div>
    );
}

function Summary({ label, value, icon }) {
    return (
        <div className="soft-rise rounded-3xl border border-black/10 bg-white p-5 shadow-[0_18px_55px_rgba(0,0,0,.06)]">
            <div className="flex items-center justify-between gap-3">
                <span className="text-[10px] font-black uppercase tracking-widest text-[#666]">{label}</span>
                <span className="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#FCCA00]/20 text-[#8b6c00]">
                    <i className={`fas ${icon}`} />
                </span>
            </div>
            <strong className="mt-3 block text-4xl font-black text-black">{value}</strong>
        </div>
    );
}

function Badge({ children, tone = 'neutral' }) {
    const styles = {
        green: 'bg-green-100 text-green-800',
        yellow: 'bg-yellow-100 text-yellow-900',
        neutral: 'bg-black/5 text-black/70',
    };
    return <span className={`rounded-full px-3 py-1.5 text-[10px] font-black uppercase tracking-wider ${styles[tone]}`}>{children}</span>;
}

function Place({ label, value, align = 'left' }) {
    return (
        <div className={align === 'right' ? 'min-w-0 md:text-right' : 'min-w-0'}>
            <p className="mb-1 text-[10px] font-black uppercase tracking-widest text-[#777]">{label}</p>
            <h2 className="mb-0 break-words text-2xl font-black leading-tight text-black">{value}</h2>
        </div>
    );
}

function Mini({ label, value, icon }) {
    return (
        <div className="rounded-2xl bg-[#f5f4ef] p-4">
            <p className="mb-1 text-[10px] font-black uppercase tracking-widest text-[#777]"><i className={`fas ${icon} mr-2 text-[#b89500]`} />{label}</p>
            <strong className="block text-sm text-black">{value}</strong>
        </div>
    );
}

function Pagination({ links }) {
    if (!links?.length) return null;
    return <div className="mt-6 flex flex-wrap gap-2">{links.map((link, index) => <Link key={`${link.label}-${index}`} href={link.url || '#'} preserveScroll className={`rounded-xl border px-4 py-2 text-xs font-bold ${link.active ? 'border-[#FCCA00] bg-[#FCCA00] text-black' : 'border-black/10 bg-white text-black/70'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`} dangerouslySetInnerHTML={{ __html: link.label }} />)}</div>;
}

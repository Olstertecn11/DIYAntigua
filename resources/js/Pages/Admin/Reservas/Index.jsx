import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';

const paymentStates = ['pendiente', 'procesando', 'pagado', 'rechazado', 'fallido', 'reembolso_pendiente'];
const tripStates = ['programado', 'completado', 'cancelado'];

function money(value) {
    return `Q${Number(value || 0).toLocaleString('es-GT', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function label(value) {
    return String(value || '').replaceAll('_', ' ');
}

export default function ReservasIndex({ reservas, stats, filters, urls }) {
    const [cancelTarget, setCancelTarget] = useState(null);
    const [motivo, setMotivo] = useState('');
    const { data, setData, get, processing } = useForm({
        search: filters.search || '',
        estado_pago: filters.estado_pago || '',
        estado_viaje: filters.estado_viaje || '',
        fecha: filters.fecha || '',
    });

    const submitFilters = (event) => {
        event.preventDefault();
        get(urls.index, { preserveState: true, preserveScroll: true });
    };

    const cancelReserva = (event) => {
        event.preventDefault();
        router.post(cancelTarget.urls.cancel, { motivo_cancelacion: motivo }, {
            preserveScroll: true,
            onSuccess: () => {
                setCancelTarget(null);
                setMotivo('');
            },
        });
    };

    return (
        <AdminLayout>
            <Head title="Reservaciones" />
            <div className="min-h-screen bg-[#050505] p-6 text-white">
                <div className="mb-8 flex flex-col justify-between gap-5 xl:flex-row">
                    <div>
                        <p className="mb-2 text-[11px] font-black uppercase tracking-[0.22em] text-[#FCCA00]">Operaciones</p>
                        <h1 className="text-3xl font-black uppercase tracking-tighter md:text-4xl">Reservaciones</h1>
                        <p className="mt-2 text-sm text-[#a3a3a3]">Control de reservas, pagos, cancelaciones y reembolsos.</p>
                    </div>
                    <div className="grid min-w-full grid-cols-2 gap-3 lg:grid-cols-4 xl:min-w-[680px]">
                        <Stat label="Total" value={stats.total} />
                        <Stat label="Pagadas" value={stats.pagadas} color="text-emerald-400" />
                        <Stat label="Programadas" value={stats.programadas} color="text-[#FCCA00]" />
                        <Stat label="Reembolsos" value={stats.reembolsos} color="text-sky-400" />
                    </div>
                </div>

                <form onSubmit={submitFilters} className="mb-6 grid grid-cols-1 gap-3 md:grid-cols-5">
                    <input
                        value={data.search}
                        onChange={(event) => setData('search', event.target.value)}
                        type="text"
                        placeholder="Codigo, cliente, correo o telefono"
                        className="w-full rounded-xl border border-[#262626] bg-[#0a0a0a] px-4 py-3 text-xs text-white outline-none transition-all focus:border-[#FCCA00] md:col-span-2"
                    />
                    <FilterSelect value={data.estado_pago} onChange={(value) => setData('estado_pago', value)} placeholder="Pago: todos" options={paymentStates} />
                    <FilterSelect value={data.estado_viaje} onChange={(value) => setData('estado_viaje', value)} placeholder="Viaje: todos" options={tripStates} />
                    <div className="flex gap-2">
                        <input
                            value={data.fecha}
                            onChange={(event) => setData('fecha', event.target.value)}
                            type="date"
                            className="min-w-0 flex-1 rounded-xl border border-[#262626] bg-[#0a0a0a] px-4 py-3 text-xs text-white outline-none focus:border-[#FCCA00]"
                        />
                        <button disabled={processing} className="rounded-xl bg-[#FCCA00] px-4 py-3 text-[10px] font-black uppercase text-black transition-all hover:bg-[#ffd83d] disabled:opacity-60">
                            Filtrar
                        </button>
                    </div>
                </form>

                <div className="overflow-hidden rounded-2xl border border-[#262626] bg-[#0a0a0a] shadow-2xl">
                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[1100px] border-collapse text-left">
                            <thead>
                                <tr className="border-b border-[#262626] bg-[#101010]">
                                    {['Reserva', 'Ruta', 'Viaje', 'Estados', 'Reembolso', 'Acciones'].map((header) => (
                                        <th key={header} className={`p-4 text-[10px] font-black uppercase tracking-widest text-[#737373] ${header === 'Acciones' ? 'text-right' : ''}`}>{header}</th>
                                    ))}
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#171717]">
                                {reservas.data.length > 0 ? reservas.data.map((reserva) => (
                                    <tr key={reserva.id} className="transition-colors hover:bg-[#101010]">
                                        <td className="p-4">
                                            <div className="flex flex-col gap-1">
                                                <Link href={reserva.urls.show} className="text-sm font-black tracking-tight text-[#FCCA00] hover:underline">{reserva.codigo_reserva}</Link>
                                                <span className="text-xs font-bold text-white">{reserva.nombre_cliente}</span>
                                                <span className="text-[10px] text-[#737373]">{reserva.correo_cliente}</span>
                                                <span className="text-[10px] text-[#525252]">{reserva.is_guest ? 'Invitado' : 'Usuario registrado'}</span>
                                                {reserva.socio && <span className="text-[10px] text-[#FCCA00]">Ref: {reserva.socio.name}</span>}
                                            </div>
                                        </td>
                                        <td className="p-4">
                                            <div className="text-xs font-bold text-white">
                                                {reserva.ruta.origen}
                                                <i className="fas fa-arrow-right mx-2 text-[8px] text-[#FCCA00]" />
                                                {reserva.ruta.destino}
                                            </div>
                                            <span className="mt-1 block text-[10px] uppercase text-[#737373]">{reserva.tipo_vehiculo} · {reserva.pasajeros} pax</span>
                                        </td>
                                        <td className="p-4">
                                            <span className="text-xs font-black text-white">{reserva.travel_at}</span>
                                            <span className="block text-[10px] text-[#737373]">{reserva.estado_viaje === 'cancelado' ? 'Cancelada' : `${reserva.hours_until_travel}h restantes`}</span>
                                        </td>
                                        <td className="p-4"><Badges reserva={reserva} /></td>
                                        <td className="p-4">
                                            {reserva.reembolso_estado ? (
                                                <>
                                                    <span className="text-xs font-black text-white">{label(reserva.reembolso_estado)}</span>
                                                    <span className="block text-[10px] text-[#737373]">{money(reserva.reembolso_monto)}</span>
                                                </>
                                            ) : <span className="text-xs text-[#737373]">Sin solicitud</span>}
                                            <span className={`mt-1 block text-[10px] ${reserva.can_cancel ? 'text-emerald-300' : 'text-red-300'}`}>
                                                {reserva.can_cancel ? 'Cancelable con reembolso' : 'Fuera de politica'}
                                            </span>
                                        </td>
                                        <td className="p-4 text-right">
                                            <div className="flex justify-end gap-2">
                                                <Link href={reserva.urls.show} className="rounded-lg border border-[#262626] bg-[#171717] p-2 text-white transition-all hover:border-[#FCCA00]">
                                                    <i className="fas fa-eye text-xs" />
                                                </Link>
                                                {reserva.can_cancel && (
                                                    <button type="button" onClick={() => setCancelTarget(reserva)} className="rounded-lg border border-red-500/25 bg-red-500/10 p-2 text-red-200 transition-all hover:bg-red-500/20">
                                                        <i className="fas fa-ban text-xs" />
                                                    </button>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                )) : (
                                    <tr><td colSpan="6" className="p-10 text-center font-bold text-[#737373]">No hay reservaciones con estos filtros.</td></tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={reservas.links} />
                </div>
            </div>

            {cancelTarget && (
                <CancelModal reserva={cancelTarget} motivo={motivo} setMotivo={setMotivo} onClose={() => setCancelTarget(null)} onSubmit={cancelReserva} />
            )}
        </AdminLayout>
    );
}

function Stat({ label: text, value, color = 'text-white' }) {
    return <div className="rounded-2xl border border-[#242424] bg-[#0d0d0d] p-4"><p className="text-[10px] font-black uppercase tracking-widest text-[#737373]">{text}</p><strong className={`text-2xl font-black ${color}`}>{value}</strong></div>;
}

function FilterSelect({ value, onChange, placeholder, options }) {
    return (
        <select value={value} onChange={(event) => onChange(event.target.value)} className="rounded-xl border border-[#262626] bg-[#0a0a0a] px-4 py-3 text-xs text-white outline-none focus:border-[#FCCA00]">
            <option value="">{placeholder}</option>
            {options.map((option) => <option key={option} value={option}>{label(option)}</option>)}
        </select>
    );
}

function Badges({ reserva }) {
    return <div className="flex flex-col gap-2"><span className="w-fit rounded-md border border-white/10 px-2 py-1 text-[9px] font-black uppercase text-white">{label(reserva.estado_pago)}</span><span className="w-fit rounded-md border border-[#FCCA00]/20 bg-[#FCCA00]/10 px-2 py-1 text-[9px] font-black uppercase text-[#FCCA00]">{reserva.estado_viaje}</span></div>;
}

function Pagination({ links }) {
    if (!links?.length) return null;
    return <div className="flex flex-wrap gap-2 border-t border-[#262626] bg-[#0d0d0d] p-4">{links.map((link, index) => <Link key={`${link.label}-${index}`} href={link.url || '#'} preserveScroll className={`rounded-lg border px-3 py-2 text-xs font-bold ${link.active ? 'border-[#FCCA00] text-[#FCCA00]' : 'border-white/10 text-white/70'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`} dangerouslySetInnerHTML={{ __html: link.label }} />)}</div>;
}

function CancelModal({ reserva, motivo, setMotivo, onClose, onSubmit }) {
    return (
        <div className="fixed inset-0 z-[120] flex items-center justify-center bg-black/75 p-4 backdrop-blur-sm">
            <div className="w-full max-w-lg rounded-3xl border border-white/10 bg-[#0a0a0a] p-6 text-white shadow-2xl">
                <h2 className="text-2xl font-black">Confirmar cancelacion</h2>
                <div className="mt-5 rounded-2xl border border-white/10 bg-white/[0.03] p-4 text-sm">
                    <div className="flex justify-between gap-4"><span className="text-[#737373]">Reserva</span><strong>{reserva.codigo_reserva}</strong></div>
                    <div className="mt-2 flex justify-between gap-4"><span className="text-[#737373]">Cliente</span><strong>{reserva.nombre_cliente}</strong></div>
                    <div className="mt-2 flex justify-between gap-4"><span className="text-[#737373]">Monto</span><strong className="text-[#FCCA00]">{money(reserva.precio_total)}</strong></div>
                    <p className="mt-4 rounded-xl bg-[#FCCA00]/10 p-3 text-xs font-bold text-[#FCCA00]">{reserva.refund_message}</p>
                </div>
                <form onSubmit={onSubmit} className="mt-5">
                    <label className="text-[10px] font-black uppercase tracking-widest text-[#737373]">Motivo opcional</label>
                    <textarea value={motivo} onChange={(event) => setMotivo(event.target.value)} maxLength="500" rows="3" className="mt-2 w-full resize-none rounded-2xl border border-white/10 bg-black px-4 py-3 text-sm text-white outline-none focus:border-[#FCCA00]" />
                    <div className="mt-5 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <button type="button" onClick={onClose} className="rounded-full border border-white/10 px-5 py-3 text-xs font-black uppercase text-white hover:bg-white/5">Volver</button>
                        <button className="rounded-full bg-red-500 px-5 py-3 text-xs font-black uppercase text-white hover:bg-red-400">Cancelar y registrar reembolso</button>
                    </div>
                </form>
            </div>
        </div>
    );
}

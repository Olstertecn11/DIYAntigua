import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';

function money(value) {
    return `Q${Number(value || 0).toLocaleString('es-GT', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function label(value) {
    return String(value || '').replaceAll('_', ' ');
}

export default function ReservaShow({ reserva, urls }) {
    const [openCancel, setOpenCancel] = useState(false);
    const [motivo, setMotivo] = useState('');

    const cancelReserva = (event) => {
        event.preventDefault();
        router.post(urls.cancel, { motivo_cancelacion: motivo }, {
            preserveScroll: true,
            onSuccess: () => setOpenCancel(false),
        });
    };

    return (
        <AdminLayout>
            <Head title={reserva.codigo_reserva} />
            <div className="min-h-screen bg-[#050505] p-6 text-white">
                <div className="mb-8 flex flex-col justify-between gap-5 lg:flex-row">
                    <div>
                        <Link href={urls.index} className="text-xs font-black uppercase tracking-widest text-[#FCCA00] hover:underline"><i className="fas fa-arrow-left mr-2" />Volver</Link>
                        <h1 className="mt-3 text-3xl font-black uppercase tracking-tighter md:text-4xl">{reserva.codigo_reserva}</h1>
                        <p className="mt-2 text-sm text-[#a3a3a3]">{reserva.nombre_cliente} · {reserva.correo_cliente}</p>
                    </div>
                    <div className="flex flex-wrap items-start gap-3">
                        <a href={urls.pdf} className="rounded-xl border border-[#262626] bg-[#101010] px-4 py-3 text-xs font-black uppercase text-white hover:border-[#FCCA00]"><i className="fas fa-file-pdf mr-2" />PDF</a>
                        {reserva.can_cancel ? <button type="button" onClick={() => setOpenCancel(true)} className="rounded-xl bg-red-500 px-4 py-3 text-xs font-black uppercase text-white hover:bg-red-400">Cancelar</button> : <span className="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-xs font-black uppercase text-red-200">No cancelable con reembolso</span>}
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-5 xl:grid-cols-3">
                    <section className="rounded-3xl border border-[#262626] bg-[#0a0a0a] p-5 xl:col-span-2">
                        <div className="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                            <Metric label="Pago" value={label(reserva.estado_pago)} />
                            <Metric label="Viaje" value={reserva.estado_viaje} />
                            <Metric label="Total" value={money(reserva.precio_total)} highlight />
                        </div>
                        <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <InfoCard title="Cliente" rows={[['Nombre', reserva.nombre_cliente], ['Correo', reserva.correo_cliente], ['Telefono', reserva.telefono_cliente], ['Cuenta', reserva.user.name], ['Referido por', reserva.socio?.name || 'Sin socio']]} />
                            <InfoCard title="Viaje" rows={[['Ruta', `${reserva.ruta.origen} -> ${reserva.ruta.destino}`], ['Fecha', reserva.travel_date], ['Restante', reserva.estado_viaje === 'cancelado' ? 'Cancelada' : `${reserva.hours_until_travel} horas`], ['Vehiculo', `${String(reserva.tipo_vehiculo || '').toUpperCase()} · ${reserva.pasajeros} pax`]]} />
                        </div>
                        <div className="mt-5 rounded-2xl border border-[#242424] bg-[#101010] p-5">
                            <h2 className="mb-4 text-sm font-black uppercase tracking-widest text-[#FCCA00]">Notas operativas</h2>
                            <pre className="whitespace-pre-wrap font-sans text-sm leading-relaxed text-[#d4d4d4]">{reserva.notas_adicionales}</pre>
                        </div>
                    </section>
                    <aside className="space-y-5">
                        <InfoCard title="Pago QPayPro" rows={reserva.transaction ? [['Estado', String(reserva.transaction.status).toUpperCase()], ['Referencia', reserva.transaction.reference], ['Tarjeta', `${String(reserva.transaction.card_brand).toUpperCase()} **** ${reserva.transaction.card_last_four || ''}`], ['Respuesta', reserva.transaction.response_message]] : [['Estado', 'Sin transacciones registradas']]} />
                        <InfoCard title="Cancelacion" rows={[['Politica', reserva.policy_label], ['Cancelado', reserva.cancelado_at || 'No'], ['Reembolso', reserva.reembolso_estado ? label(reserva.reembolso_estado) : 'Sin solicitud'], ['Monto', money(reserva.reembolso_monto)]]} />
                        <InfoCard title="Comision socio" rows={[['Socio', reserva.socio?.name || 'No referido'], ['Ganancia', money(reserva.comision_socio)]]} />
                    </aside>
                </div>
            </div>
            {openCancel && (
                <div className="fixed inset-0 z-[120] flex items-center justify-center bg-black/75 p-4 backdrop-blur-sm">
                    <div className="w-full max-w-lg rounded-3xl border border-white/10 bg-[#0a0a0a] p-6 text-white shadow-2xl">
                        <h2 className="text-2xl font-black">Cancelar {reserva.codigo_reserva}</h2>
                        <p className="mt-2 text-sm leading-6 text-[#a3a3a3]">Si el pago esta aprobado, se registrara un reembolso pendiente para gestion administrativa.</p>
                        <form onSubmit={cancelReserva} className="mt-5">
                            <textarea value={motivo} onChange={(event) => setMotivo(event.target.value)} maxLength="500" rows="3" className="w-full resize-none rounded-2xl border border-white/10 bg-black px-4 py-3 text-sm text-white outline-none focus:border-[#FCCA00]" placeholder="Motivo opcional" />
                            <div className="mt-5 flex justify-end gap-3">
                                <button type="button" onClick={() => setOpenCancel(false)} className="rounded-full border border-white/10 px-5 py-3 text-xs font-black uppercase text-white hover:bg-white/5">Volver</button>
                                <button className="rounded-full bg-red-500 px-5 py-3 text-xs font-black uppercase text-white hover:bg-red-400">Confirmar cancelacion</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}

function Metric({ label: text, value, highlight = false }) {
    return <div className="rounded-2xl border border-[#242424] bg-[#101010] p-4"><p className="text-[10px] font-black uppercase tracking-widest text-[#737373]">{text}</p><strong className={`text-lg font-black uppercase ${highlight ? 'text-[#FCCA00]' : ''}`}>{value}</strong></div>;
}

function InfoCard({ title, rows }) {
    return <section className="rounded-2xl border border-[#242424] bg-[#101010] p-5"><h2 className="mb-4 text-sm font-black uppercase tracking-widest text-[#FCCA00]">{title}</h2><dl className="space-y-3 text-sm">{rows.map(([key, value]) => <div key={key}><dt className="text-[10px] font-black uppercase text-[#737373]">{key}</dt><dd className="break-words">{value || 'N/A'}</dd></div>)}</dl></section>;
}

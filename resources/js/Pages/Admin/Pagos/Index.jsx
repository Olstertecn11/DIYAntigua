import { Head, Link } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';

function money(value) {
    return `Q${Number(value || 0).toLocaleString('es-GT', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

export default function PagosIndex({ afiliados, reservasReferidas, totales }) {
    return (
        <AdminLayout>
            <Head title="Pagos y comisiones" />
            <div className="min-h-screen bg-black p-6 text-white md:p-8">
                <div className="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p className="text-[10px] font-black uppercase tracking-[0.24em] text-[#fcca00]">Negocio</p>
                        <h1 className="mt-2 text-3xl font-black tracking-tight">Pagos y comisiones</h1>
                        <p className="mt-2 text-sm text-[#a1a1a1]">Control de ventas referidas por socios Airbnb y ganancias acumuladas.</p>
                    </div>
                </div>

                <div className="mb-8 grid gap-4 md:grid-cols-3">
                    <TotalCard icon="fa-chart-line" label="Ventas referidas" value={money(totales.ventas)} tone="yellow" />
                    <TotalCard icon="fa-hand-holding-dollar" label="Comisiones" value={money(totales.comisiones)} tone="green" />
                    <TotalCard icon="fa-circle-check" label="Reservas pagadas" value={totales.reservas} tone="white" />
                </div>

                <section className="mb-8 rounded-2xl border border-white/10 bg-[#0a0a0a] p-5 shadow-[0_24px_80px_rgba(0,0,0,.24)]">
                    <h2 className="mb-4 text-sm font-black uppercase tracking-widest text-white">Socios</h2>
                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[850px] text-left text-sm">
                            <thead className="border-b border-white/10 text-[10px] uppercase tracking-widest text-[#737373]">
                                <tr>
                                    <th className="py-3">Socio</th>
                                    <th className="py-3">Pago</th>
                                    <th className="py-3 text-center">Reservas</th>
                                    <th className="py-3 text-right">Ventas</th>
                                    <th className="py-3 text-right">Ganancia</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-white/5">
                                {afiliados.length > 0 ? afiliados.map((afiliado) => (
                                    <tr key={afiliado.id} className="transition hover:bg-white/[0.03]">
                                        <td className="py-4">
                                            <div className="flex items-center gap-3">
                                                <span className="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#fcca00] text-sm font-black text-black">
                                                    {String(afiliado.nombre_comercial || 'S').slice(0, 1).toUpperCase()}
                                                </span>
                                                <div>
                                                    <div className="font-black">{afiliado.nombre_comercial}</div>
                                                    <div className="text-xs text-[#737373]">{afiliado.user?.email}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="py-4">
                                            <div className="inline-flex rounded-full border border-cyan-300/20 bg-cyan-300/10 px-3 py-1 text-[11px] font-black uppercase tracking-wider text-cyan-200">{afiliado.metodo_pago || 'Sin metodo'}</div>
                                            <div className="mt-1 text-[11px] text-[#737373]">{afiliado.titular_pago}</div>
                                        </td>
                                        <td className="py-4 text-center"><span className="rounded-full bg-white/10 px-3 py-1 font-black">{afiliado.reservas_pagadas_count}</span></td>
                                        <td className="py-4 text-right font-black text-white">{money(afiliado.ventas_referidas_total)}</td>
                                        <td className="py-4 text-right"><span className="rounded-full bg-emerald-400/10 px-3 py-1 font-black text-emerald-300">{money(afiliado.comisiones_total)}</span></td>
                                    </tr>
                                )) : <tr><td colSpan="5" className="py-10 text-center text-[#737373]">Aun no hay socios registrados.</td></tr>}
                            </tbody>
                        </table>
                    </div>
                </section>

                <section className="rounded-2xl border border-white/10 bg-[#0a0a0a] p-5 shadow-[0_24px_80px_rgba(0,0,0,.24)]">
                    <h2 className="mb-4 text-sm font-black uppercase tracking-widest text-white">Reservas referidas</h2>
                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[900px] text-left text-sm">
                            <thead className="border-b border-white/10 text-[10px] uppercase tracking-widest text-[#737373]">
                                <tr>
                                    <th className="py-3">Reserva</th>
                                    <th className="py-3">Socio</th>
                                    <th className="py-3">Ruta</th>
                                    <th className="py-3">Estado</th>
                                    <th className="py-3 text-right">Total</th>
                                    <th className="py-3 text-right">Comision</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-white/5">
                                {reservasReferidas.data.length > 0 ? reservasReferidas.data.map((reserva) => (
                                    <tr key={reserva.id} className="transition hover:bg-white/[0.03]">
                                        <td className="py-4"><Link href={reserva.urls.show} className="font-black text-white hover:text-[#fcca00]">{reserva.codigo_reserva}</Link><div className="text-xs text-[#737373]">{reserva.nombre_cliente}</div></td>
                                        <td className="py-4"><span className="rounded-full bg-[#fcca00]/10 px-3 py-1 text-xs font-black text-[#fcca00]">{reserva.socio}</span></td>
                                        <td className="py-4 text-[#d4d4d4]">{reserva.ruta}</td>
                                        <td className="py-4"><span className="rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1 text-[11px] font-black uppercase tracking-wider text-emerald-300">{reserva.estado_pago} / {reserva.estado_viaje}</span></td>
                                        <td className="py-4 text-right">{money(reserva.precio_total)}</td>
                                        <td className="py-4 text-right"><span className="rounded-full bg-[#fcca00]/10 px-3 py-1 font-black text-[#fcca00]">{money(reserva.comision_socio)}</span></td>
                                    </tr>
                                )) : <tr><td colSpan="6" className="py-10 text-center text-[#737373]">Aun no hay reservas referidas.</td></tr>}
                            </tbody>
                        </table>
                    </div>
                    <Pagination links={reservasReferidas.links} />
                </section>
            </div>
        </AdminLayout>
    );
}

function TotalCard({ icon, label, value, tone = 'white' }) {
    const tones = {
        yellow: 'border-[#fcca00]/30 bg-[#fcca00]/10 text-[#fcca00]',
        green: 'border-emerald-300/30 bg-emerald-300/10 text-emerald-300',
        white: 'border-white/15 bg-white/10 text-white',
    };

    return (
        <div className={`rounded-2xl border p-5 shadow-[0_20px_60px_rgba(0,0,0,.18)] ${tones[tone] || tones.white}`}>
            <div className="mb-4 flex h-11 w-11 items-center justify-center rounded-2xl bg-black/30">
                <i className={`fas ${icon}`} />
            </div>
            <p className="text-[10px] font-black uppercase tracking-widest text-white/55">{label}</p>
            <strong className="mt-2 block text-3xl font-black text-white">{value}</strong>
        </div>
    );
}

function Pagination({ links }) {
    if (!links?.length) return null;
    return <div className="mt-4 flex flex-wrap gap-2">{links.map((link, index) => <Link key={`${link.label}-${index}`} href={link.url || '#'} preserveScroll className={`rounded-lg border px-3 py-2 text-xs font-bold ${link.active ? 'border-[#FCCA00] text-[#FCCA00]' : 'border-white/10 text-white/70'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`} dangerouslySetInnerHTML={{ __html: link.label }} />)}</div>;
}

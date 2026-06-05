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
                    <TotalCard label="Ventas referidas" value={money(totales.ventas)} />
                    <TotalCard label="Comisiones" value={money(totales.comisiones)} yellow />
                    <TotalCard label="Reservas pagadas" value={totales.reservas} />
                </div>

                <section className="mb-8 rounded-2xl border border-white/10 bg-[#0a0a0a] p-5">
                    <h2 className="mb-4 text-sm font-black uppercase tracking-widest">Socios</h2>
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
                                    <tr key={afiliado.id}>
                                        <td className="py-4"><div className="font-bold">{afiliado.nombre_comercial}</div><div className="text-xs text-[#737373]">{afiliado.user?.email}</div></td>
                                        <td className="py-4"><div className="text-xs text-[#d4d4d4]">{afiliado.metodo_pago}</div><div className="text-[11px] text-[#737373]">{afiliado.titular_pago}</div></td>
                                        <td className="py-4 text-center">{afiliado.reservas_pagadas_count}</td>
                                        <td className="py-4 text-right">{money(afiliado.ventas_referidas_total)}</td>
                                        <td className="py-4 text-right font-black text-[#fcca00]">{money(afiliado.comisiones_total)}</td>
                                    </tr>
                                )) : <tr><td colSpan="5" className="py-10 text-center text-[#737373]">Aun no hay socios registrados.</td></tr>}
                            </tbody>
                        </table>
                    </div>
                </section>

                <section className="rounded-2xl border border-white/10 bg-[#0a0a0a] p-5">
                    <h2 className="mb-4 text-sm font-black uppercase tracking-widest">Reservas referidas</h2>
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
                                    <tr key={reserva.id}>
                                        <td className="py-4"><Link href={reserva.urls.show} className="font-black text-white hover:text-[#fcca00]">{reserva.codigo_reserva}</Link><div className="text-xs text-[#737373]">{reserva.nombre_cliente}</div></td>
                                        <td className="py-4">{reserva.socio}</td>
                                        <td className="py-4 text-[#d4d4d4]">{reserva.ruta}</td>
                                        <td className="py-4"><span className="rounded-full border border-white/10 px-3 py-1 text-[11px] font-bold">{reserva.estado_pago} / {reserva.estado_viaje}</span></td>
                                        <td className="py-4 text-right">{money(reserva.precio_total)}</td>
                                        <td className="py-4 text-right font-black text-[#fcca00]">{money(reserva.comision_socio)}</td>
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

function TotalCard({ label, value, yellow = false }) {
    return <div className="rounded-2xl border border-white/10 bg-[#0a0a0a] p-5"><p className="text-[10px] font-black uppercase tracking-widest text-[#737373]">{label}</p><strong className={`mt-2 block text-3xl font-black ${yellow ? 'text-[#fcca00]' : ''}`}>{value}</strong></div>;
}

function Pagination({ links }) {
    if (!links?.length) return null;
    return <div className="mt-4 flex flex-wrap gap-2">{links.map((link, index) => <Link key={`${link.label}-${index}`} href={link.url || '#'} preserveScroll className={`rounded-lg border px-3 py-2 text-xs font-bold ${link.active ? 'border-[#FCCA00] text-[#FCCA00]' : 'border-white/10 text-white/70'} ${!link.url ? 'pointer-events-none opacity-40' : ''}`} dangerouslySetInnerHTML={{ __html: link.label }} />)}</div>;
}

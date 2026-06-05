import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import SocioLayout from '@/Layouts/SocioLayout';

function money(value) {
    return `Q${Number(value || 0).toLocaleString('es-GT', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

export default function SocioDashboard({ user, info, referralLink, stats, reservas, urls }) {
    const [copied, setCopied] = useState(false);
    const displayName = info?.nombre_comercial || user.name;

    const copyLink = async () => {
        await navigator.clipboard.writeText(referralLink);
        setCopied(true);
        setTimeout(() => setCopied(false), 2000);
    };

    return (
        <SocioLayout>
            <Head title="Mi Panel" />
            <div className="min-h-screen bg-black p-8 text-white">
                <header className="mb-10">
                    <div className="flex items-center space-x-4">
                        <div className="flex h-12 w-12 items-center justify-center rounded-full border border-yellow-500/50 bg-yellow-500/10 text-xl font-bold text-yellow-500">{displayName.charAt(0)}</div>
                        <div>
                            <h1 className="text-3xl font-bold tracking-tight">Bienvenido, {displayName}</h1>
                            <p className="text-sm text-gray-500">Panel de control de afiliado para Antigua Transfers.</p>
                        </div>
                    </div>
                </header>

                <div className="mb-10 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <Stat label="Mis Ganancias" value={money(stats.ganancias)} color="text-green-500" />
                    <Stat label="Mi Comision" value={`${stats.comision}%`} color="text-yellow-500" />
                    <Stat label="Viajes Referidos" value={stats.referidos_count} />
                </div>
                <div className="mb-10 grid grid-cols-1 gap-6 md:grid-cols-2">
                    <Stat label="Ventas Generadas" value={money(stats.ventas)} />
                    <Stat label="Estado" value={info?.activo ? 'Activo' : 'Inactivo'} color={info?.activo ? 'text-green-500' : 'text-red-400'} />
                </div>

                <section className="relative overflow-hidden rounded-2xl border border-white/10 bg-[#0a0a0a] p-8 shadow-2xl">
                    <h2 className="mb-4 flex items-center text-xl font-bold"><i className="fas fa-link mr-3 text-blue-500" /> Enlace de Reservacion para tu Airbnb</h2>
                    <p className="mb-6 max-w-2xl text-sm text-gray-400">Usa este enlace unico. Cuando tus huespedes reserven a traves de el, el sistema te asignara automaticamente tu comision.</p>
                    <div className="max-w-2xl">
                        <div className="flex items-center rounded-lg border border-white/20 bg-black p-1">
                            <input type="text" readOnly value={referralLink} className="w-full border-none bg-transparent px-4 py-3 font-mono text-sm text-gray-300 outline-none" />
                            <button type="button" onClick={copyLink} className={`whitespace-nowrap rounded-md px-6 py-2 text-[10px] font-bold uppercase text-black transition-all ${copied ? 'bg-green-600 text-white' : 'bg-white hover:bg-gray-200'}`}>{copied ? 'Copiado' : 'Copiar Link'}</button>
                        </div>
                    </div>
                </section>

                <section className="mt-8 rounded-2xl border border-white/10 bg-[#0a0a0a] p-8 shadow-2xl">
                    <div className="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div><h2 className="text-xl font-bold">Reservas referidas</h2><p className="text-sm text-gray-500">Historial de clientes que llegaron desde tu enlace.</p></div>
                        <Link href={urls.profile} className="inline-flex items-center justify-center rounded-md border border-yellow-500/40 px-4 py-2 text-xs font-black uppercase tracking-widest text-yellow-500 transition hover:bg-yellow-500 hover:text-black">Perfil de pago</Link>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[760px] text-left text-sm text-gray-400">
                            <thead className="border-b border-white/10 text-[10px] uppercase tracking-widest text-gray-500"><tr><th className="py-3">Reserva</th><th className="py-3">Ruta</th><th className="py-3">Estado</th><th className="py-3 text-right">Venta</th><th className="py-3 text-right">Comision</th></tr></thead>
                            <tbody className="divide-y divide-white/5">
                                {reservas.length > 0 ? reservas.map((reserva) => (
                                    <tr key={reserva.id}><td className="py-4"><div className="font-bold text-white">{reserva.codigo_reserva}</div><div className="text-xs text-gray-500">{reserva.nombre_cliente}</div></td><td className="py-4">{reserva.ruta}</td><td className="py-4"><span className="rounded-full border border-white/10 px-3 py-1 text-[11px] font-bold">{reserva.estado_pago} / {reserva.estado_viaje}</span></td><td className="py-4 text-right">{money(reserva.precio_total)}</td><td className="py-4 text-right font-black text-yellow-500">{money(reserva.comision_socio)}</td></tr>
                                )) : <tr><td colSpan="5" className="py-10 text-center text-gray-500">Aun no hay reservas desde tu enlace.</td></tr>}
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </SocioLayout>
    );
}

function Stat({ label, value, color = 'text-white' }) {
    return <div className="rounded-xl border border-white/10 bg-[#0a0a0a] p-6 transition-colors hover:border-white/20"><p className="text-xs font-black uppercase tracking-widest text-gray-500">{label}</p><p className={`mt-2 font-mono text-3xl font-bold ${color}`}>{value}</p></div>;
}

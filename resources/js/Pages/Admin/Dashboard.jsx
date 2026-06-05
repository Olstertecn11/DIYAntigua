import { Head, Link, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';

function money(value) {
    return `Q${Number(value || 0).toLocaleString('es-GT', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

export default function Dashboard({ afiliados, reservasStats, proximasReservas, urls }) {
    const [openModal, setOpenModal] = useState(false);
    const { data, setData, post, processing, reset, errors } = useForm({
        name: '',
        email: '',
        nombre_comercial: '',
        comision: 10,
        password: '',
    });

    const submit = (event) => {
        event.preventDefault();

        post(urls.storeAfiliado, {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setOpenModal(false);
            },
        });
    };

    const statCards = [
        ['Total Afiliados', afiliados.length, 'text-white'],
        ['Reservas Totales', reservasStats?.total || 0, 'text-white'],
        ['Viajes Hoy', reservasStats?.hoy || 0, 'text-[#FCCA00]'],
        ['Reembolsos Pendientes', reservasStats?.reembolsos || 0, 'text-cyan-400'],
        ['Comisiones', money(reservasStats?.comisiones), 'text-[#FCCA00]'],
    ];

    return (
        <AdminLayout>
            <Head title="Panel de Administracion" />

            <div className="mx-auto max-w-7xl p-8">
                <header className="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight text-white">Panel de Administracion</h1>
                        <p className="text-sm text-[#a1a1a1]">Gestion de socios, reservas y logistica de traslados.</p>
                    </div>
                    <button
                        type="button"
                        onClick={() => setOpenModal(true)}
                        className="inline-flex items-center justify-center rounded-md bg-white px-6 py-2 text-xs font-bold text-black shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-all hover:bg-gray-200"
                    >
                        <i className="fas fa-plus mr-2" />
                        Nuevo Socio
                    </button>
                </header>

                <div className="mb-8 grid grid-cols-1 gap-4 md:grid-cols-5">
                    {statCards.map(([label, value, color]) => (
                        <div key={label} className="rounded-xl border border-[#262626] bg-[#0a0a0a] p-6">
                            <p className="mb-1 text-[10px] font-bold uppercase tracking-widest text-[#737373]">{label}</p>
                            <h3 className={`text-2xl font-bold ${color}`}>{value}</h3>
                        </div>
                    ))}
                </div>

                <section className="mb-8 overflow-hidden rounded-xl border border-[#262626] bg-[#0a0a0a] shadow-2xl">
                    <div className="flex items-center justify-between border-b border-[#262626] bg-[#050505]/50 p-6">
                        <h2 className="text-sm font-bold uppercase tracking-widest text-white">Proximas Reservas</h2>
                        <Link href={urls.reservas} className="text-xs font-black uppercase text-[#FCCA00]">Ver todas</Link>
                    </div>
                    <div className="divide-y divide-[#171717]">
                        {proximasReservas.length > 0 ? proximasReservas.map((reserva) => (
                            <Link
                                key={reserva.id}
                                href={reserva.urls.show}
                                className="grid grid-cols-1 gap-3 p-4 no-underline transition hover:bg-[#101010] md:grid-cols-4"
                            >
                                <span className="text-sm font-black text-[#FCCA00]">{reserva.codigo_reserva}</span>
                                <span className="text-sm font-bold text-white">{reserva.nombre_cliente}</span>
                                <span className="text-sm text-[#a3a3a3]">{reserva.ruta}</span>
                                <span className="text-sm font-black text-white md:text-right">{reserva.fecha}</span>
                            </Link>
                        )) : (
                            <div className="p-6 text-sm font-bold text-[#737373]">No hay reservas proximas.</div>
                        )}
                    </div>
                </section>

                <section className="overflow-hidden rounded-xl border border-[#262626] bg-[#0a0a0a] shadow-2xl">
                    <div className="border-b border-[#262626] bg-[#050505]/50 p-6">
                        <h2 className="text-sm font-bold uppercase tracking-widest text-white">Gestion de Afiliados Activos</h2>
                    </div>
                    <AfiliadosTable afiliados={afiliados} />
                </section>
            </div>

            {openModal && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm">
                    <div className="w-full max-w-md overflow-hidden rounded-xl border border-[#262626] bg-[#0a0a0a] shadow-[0_0_50px_rgba(0,0,0,0.5)]">
                        <div className="p-8">
                            <h2 className="mb-1 text-xl font-bold text-white">Registrar Nuevo Socio</h2>
                            <p className="mb-6 text-xs text-[#a1a1a1]">El socio recibira sus credenciales por correo.</p>

                            <form onSubmit={submit}>
                                <div className="space-y-4">
                                    <TextInput label="Nombre" value={data.name} error={errors.name} onChange={(value) => setData('name', value)} required />
                                    <TextInput label="Email" type="email" value={data.email} error={errors.email} onChange={(value) => setData('email', value)} required />
                                    <div className="grid grid-cols-2 gap-4">
                                        <TextInput label="Comercial" value={data.nombre_comercial} error={errors.nombre_comercial} onChange={(value) => setData('nombre_comercial', value)} required />
                                        <TextInput label="Comision %" type="number" value={data.comision} error={errors.comision} onChange={(value) => setData('comision', value)} required />
                                    </div>
                                    <TextInput label="Contrasena" type="password" value={data.password} error={errors.password} onChange={(value) => setData('password', value)} required />
                                </div>

                                <div className="mt-8 flex gap-3">
                                    <button
                                        type="button"
                                        onClick={() => setOpenModal(false)}
                                        className="flex-1 rounded-md border border-[#262626] px-4 py-2 text-xs font-bold text-[#a1a1a1] hover:bg-[#111]"
                                    >
                                        Cancelar
                                    </button>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="flex-1 rounded-md bg-white px-4 py-2 text-xs font-bold text-black transition-all hover:bg-gray-200 disabled:cursor-not-allowed disabled:opacity-60"
                                    >
                                        {processing ? 'Guardando...' : 'Guardar Socio'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}

function AfiliadosTable({ afiliados }) {
    return (
        <div className="overflow-hidden rounded-xl border border-white/10 bg-[#0a0a0a]">
            <div className="overflow-x-auto">
                <table className="w-full min-w-[980px] text-left text-sm text-gray-400">
                    <thead className="border-b border-white/10 bg-white/[0.02] text-xs uppercase tracking-widest text-gray-500">
                        <tr>
                            <th className="px-6 py-4 font-medium">Socio / Empresa</th>
                            <th className="px-6 py-4 font-medium">Contacto</th>
                            <th className="px-6 py-4 text-center font-medium">Comision</th>
                            <th className="px-6 py-4 text-center font-medium">Reservas</th>
                            <th className="px-6 py-4 text-right font-medium">Ganancias</th>
                            <th className="px-6 py-4 text-center font-medium">Estado</th>
                            <th className="px-6 py-4 text-right font-medium">Acciones</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-white/5">
                        {afiliados.length > 0 ? afiliados.map((afiliado) => (
                            <tr key={afiliado.id} className="group transition-colors hover:bg-white/[0.02]">
                                <td className="whitespace-nowrap px-6 py-4">
                                    <div className="flex items-center">
                                        <div className="flex h-8 w-8 items-center justify-center rounded-full border border-white/10 bg-gradient-to-br from-gray-800 to-black text-xs font-bold text-white transition-colors group-hover:border-yellow-500/50">
                                            {(afiliado.nombre_comercial || 'S').charAt(0)}
                                        </div>
                                        <div className="ml-4">
                                            <div className="text-sm font-medium text-white">{afiliado.nombre_comercial || 'Sin nombre'}</div>
                                            <div className="text-xs text-gray-500">Codigo: {afiliado.codigo_referido || 'Pendiente'}</div>
                                        </div>
                                    </div>
                                </td>
                                <td className="px-6 py-4">
                                    <div className="flex flex-col">
                                        <span className="text-gray-300">{afiliado.user?.email || 'N/A'}</span>
                                        <span className="text-[10px] italic text-gray-500">{afiliado.user?.name || ''}</span>
                                    </div>
                                </td>
                                <td className="px-6 py-4 text-center">
                                    <span className="inline-flex items-center rounded-md border border-white/10 bg-white/[0.03] px-2 py-1 text-xs font-medium text-yellow-500">
                                        {Number(afiliado.comision_porcentaje || 0).toFixed(2)}%
                                    </span>
                                </td>
                                <td className="px-6 py-4 text-center font-bold text-white">{afiliado.reservas_referidas_count || 0}</td>
                                <td className="px-6 py-4 text-right font-black text-yellow-500">{money(afiliado.comisiones_total)}</td>
                                <td className="px-6 py-4 text-center">
                                    <div className="flex items-center justify-center space-x-2">
                                        <span className={[
                                            'h-1.5 w-1.5 rounded-full',
                                            afiliado.activo ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]' : 'bg-red-500 opacity-50',
                                        ].join(' ')}
                                        />
                                        <span className={`text-xs font-medium ${afiliado.activo ? 'text-green-500' : 'text-gray-500'}`}>
                                            {afiliado.activo ? 'Online' : 'Offline'}
                                        </span>
                                    </div>
                                </td>
                                <td className="px-6 py-4 text-right">
                                    <div className="flex justify-end space-x-2">
                                        <button type="button" className="flex h-8 w-8 items-center justify-center rounded-md border border-white/10 bg-transparent text-gray-400 transition-all hover:border-white/30 hover:text-white">
                                            <i className="fas fa-edit text-xs" />
                                        </button>
                                        <button type="button" className="flex h-8 w-8 items-center justify-center rounded-md border border-white/10 bg-transparent text-gray-400 transition-all hover:border-red-500/50 hover:text-red-500">
                                            <i className="fas fa-trash text-xs" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        )) : (
                            <tr>
                                <td colSpan="7" className="px-6 py-12 text-center">
                                    <div className="flex flex-col items-center">
                                        <i className="fas fa-folder-open mb-3 text-3xl text-gray-700" />
                                        <span className="text-sm text-gray-500">No se encontraron afiliados en Antigua Transfers.</span>
                                    </div>
                                </td>
                            </tr>
                        )}
                    </tbody>
                </table>
            </div>
        </div>
    );
}

function TextInput({ label, value, onChange, error, type = 'text', required = false }) {
    return (
        <div>
            <label className="mb-1 block text-[10px] font-bold uppercase tracking-widest text-[#737373]">{label}</label>
            <input
                type={type}
                value={value}
                onChange={(event) => onChange(event.target.value)}
                required={required}
                className="w-full rounded-md border border-[#262626] bg-black px-3 py-2 text-sm text-white transition-all focus:border-white focus:outline-none focus:ring-0"
            />
            {error && <p className="mt-2 text-xs font-bold text-red-400">{error}</p>}
        </div>
    );
}

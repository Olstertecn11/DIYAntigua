import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';

export default function ConductoresIndex({ conductores, urls }) {
    const [openModal, setOpenModal] = useState(false);
    const { data, setData, post, processing, reset, errors } = useForm({
        nombre: '',
        telefono: '',
        vehiculo_modelo: '',
        placa: '',
    });

    const submit = (event) => {
        event.preventDefault();

        post(urls.store, {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setOpenModal(false);
            },
        });
    };

    const destroy = (conductor) => {
        if (!window.confirm('Eliminar conductor?')) {
            return;
        }

        router.delete(conductor.urls.destroy, { preserveScroll: true });
    };

    return (
        <AdminLayout>
            <Head title="Conductores" />

            <div className="mx-auto max-w-7xl p-8">
                <header className="mb-10 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight text-white">Conductores</h1>
                        <p className="text-sm text-[#a1a1a1]">Gestion de flota y personal operativo.</p>
                    </div>
                    <button
                        type="button"
                        onClick={() => setOpenModal(true)}
                        className="inline-flex items-center justify-center rounded-md bg-white px-6 py-2 text-xs font-bold text-black shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-all hover:bg-gray-200"
                    >
                        <i className="fas fa-plus mr-2" />
                        Nuevo Conductor
                    </button>
                </header>

                <div className="overflow-hidden rounded-xl border border-[#262626] bg-[#0a0a0a] shadow-2xl">
                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[780px] text-left text-sm">
                            <thead className="border-b border-[#262626] bg-[#050505] text-[10px] uppercase tracking-widest text-[#737373]">
                                <tr>
                                    <th className="px-6 py-4">Conductor</th>
                                    <th className="px-6 py-4">Vehiculo / Placa</th>
                                    <th className="px-6 py-4">Estado</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#262626]">
                                {conductores.length > 0 ? conductores.map((conductor) => (
                                    <tr key={conductor.id} className="transition-colors hover:bg-[#111]">
                                        <td className="px-6 py-4">
                                            <div className="font-medium text-white">{conductor.nombre}</div>
                                            <div className="text-[10px] text-[#737373]">{conductor.telefono}</div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="text-[#a1a1a1]">{conductor.vehiculo_modelo || 'N/A'}</div>
                                            <div className="text-[10px] font-bold uppercase tracking-tighter text-yellow-500">{conductor.placa}</div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className={[
                                                'rounded-full px-2 py-0.5 text-[9px] font-bold uppercase',
                                                conductor.estado === 'activo'
                                                    ? 'bg-green-500/10 text-green-500'
                                                    : 'bg-red-500/10 text-red-500',
                                            ].join(' ')}
                                            >
                                                {conductor.estado}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <button
                                                type="button"
                                                onClick={() => destroy(conductor)}
                                                className="text-xs font-bold uppercase text-red-500/50 transition-colors hover:text-red-500"
                                            >
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan="4" className="px-6 py-10 text-center text-sm font-semibold text-[#737373]">
                                            No hay conductores registrados.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {openModal && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm">
                    <div className="w-full max-w-md overflow-hidden rounded-xl border border-[#262626] bg-[#0a0a0a]">
                        <div className="p-8">
                            <h2 className="mb-6 text-xl font-bold text-white">Registrar Conductor</h2>
                            <form onSubmit={submit}>
                                <div className="space-y-4">
                                    <Input label="Nombre Completo" value={data.nombre} error={errors.nombre} onChange={(value) => setData('nombre', value)} required />
                                    <Input label="Telefono / WhatsApp" value={data.telefono} error={errors.telefono} onChange={(value) => setData('telefono', value)} required />
                                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <Input label="Modelo Vehiculo" placeholder="Ej. Toyota Fortuner" value={data.vehiculo_modelo} error={errors.vehiculo_modelo} onChange={(value) => setData('vehiculo_modelo', value)} />
                                        <Input label="Placa" placeholder="P-000XXX" value={data.placa} error={errors.placa} onChange={(value) => setData('placa', value)} required />
                                    </div>
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
                                        {processing ? 'Guardando...' : 'Guardar'}
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

function Input({ label, value, onChange, error, required = false, placeholder = '' }) {
    return (
        <div>
            <label className="mb-1 block text-[10px] font-bold uppercase tracking-widest text-[#737373]">{label}</label>
            <input
                type="text"
                value={value}
                onChange={(event) => onChange(event.target.value)}
                required={required}
                placeholder={placeholder}
                className="w-full rounded-md border border-[#262626] bg-black px-3 py-2 text-sm text-white transition-all focus:border-white focus:outline-none"
            />
            {error && <p className="mt-2 text-xs font-bold text-red-400">{error}</p>}
        </div>
    );
}

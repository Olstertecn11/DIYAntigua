import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';

function initials(name) {
    return (name || 'N/A').slice(0, 3).toUpperCase();
}

export default function LugaresIndex({ lugares, urls }) {
    const [openModal, setOpenModal] = useState(false);
    const [editUrl, setEditUrl] = useState(null);
    const { data, setData, post, put, processing, reset, errors } = useForm({
        nombre: '',
        ciudad: '',
        estado: '',
    });

    const openCreate = () => {
        reset();
        setData({ nombre: '', ciudad: '', estado: '' });
        setEditUrl(null);
        setOpenModal(true);
    };

    const openEdit = (lugar) => {
        setData({
            nombre: lugar.nombre || '',
            ciudad: lugar.ciudad || '',
            estado: lugar.estado || '',
        });
        setEditUrl(lugar.urls.update);
        setOpenModal(true);
    };

    const submit = (event) => {
        event.preventDefault();

        const options = {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setOpenModal(false);
                setEditUrl(null);
            },
        };

        if (editUrl) {
            put(editUrl, options);
        } else {
            post(urls.store, options);
        }
    };

    const destroy = (lugar) => {
        if (!window.confirm('Eliminar lugar?')) {
            return;
        }

        router.delete(lugar.urls.destroy, {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout>
            <Head title="Lugares y Hoteles" />

            <div className="mx-auto max-w-7xl p-8">
                <header className="mb-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-3xl font-bold text-white">Lugares y Hoteles</h1>
                        <p className="mt-1 text-sm text-[#a1a1a1]">Registra los puntos de origen y destino del sistema.</p>
                    </div>
                    <button
                        type="button"
                        onClick={openCreate}
                        className="inline-flex items-center justify-center rounded-md bg-white px-6 py-2 text-xs font-bold text-black transition-all hover:bg-gray-200"
                    >
                        <i className="fas fa-plus mr-2" />
                        Nuevo Lugar
                    </button>
                </header>

                <div className="overflow-hidden rounded-xl border border-[#262626] bg-[#0a0a0a] shadow-2xl">
                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[720px] text-left text-sm text-white">
                            <thead className="border-b border-[#262626] bg-[#050505] text-[10px] uppercase tracking-widest text-[#737373]">
                                <tr>
                                    <th className="px-6 py-4">ID</th>
                                    <th className="px-6 py-4">Nombre del Lugar / Hotel</th>
                                    <th className="px-6 py-4">Ciudad / Depto</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#262626]">
                                {lugares.length > 0 ? lugares.map((lugar) => (
                                    <tr key={lugar.id} className="transition-colors hover:bg-[#111]">
                                        <td className="px-6 py-4 text-[#737373]">{initials(lugar.nombre)}</td>
                                        <td className="px-6 py-4 font-bold">{lugar.nombre}</td>
                                        <td className="px-6 py-4 text-[#a1a1a1]">{[lugar.ciudad, lugar.estado].filter(Boolean).join(' / ') || 'N/A'}</td>
                                        <td className="px-6 py-4 text-right">
                                            <div className="flex justify-end gap-3">
                                                <button
                                                    type="button"
                                                    onClick={() => openEdit(lugar)}
                                                    className="text-xs font-bold uppercase text-white/60 transition-colors hover:text-white"
                                                >
                                                    Editar
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => destroy(lugar)}
                                                    className="text-xs font-bold uppercase text-red-500/50 transition-colors hover:text-red-500"
                                                >
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan="4" className="px-6 py-10 text-center text-sm font-semibold text-[#737373]">
                                            No hay lugares registrados.
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
                    <div className="w-full max-w-md rounded-xl border border-[#262626] bg-[#0a0a0a] p-8">
                        <h2 className="mb-6 text-xl font-bold text-white">{editUrl ? 'Editar Punto' : 'Agregar Nuevo Punto'}</h2>
                        <form onSubmit={submit}>
                            <div className="space-y-4">
                                <div>
                                    <label className="mb-1 block text-[10px] font-bold uppercase text-[#737373]">
                                        Nombre (Ej: Aeropuerto La Aurora)
                                    </label>
                                    <input
                                        type="text"
                                        value={data.nombre}
                                        onChange={(event) => setData('nombre', event.target.value)}
                                        required
                                        className="w-full rounded-md border border-[#262626] bg-black px-3 py-2 text-sm text-white transition-all focus:border-white focus:outline-none"
                                    />
                                    {errors.nombre && <p className="mt-2 text-xs font-bold text-red-400">{errors.nombre}</p>}
                                </div>
                                <div>
                                    <label className="mb-1 block text-[10px] font-bold uppercase text-[#737373]">
                                        Ciudad o Departamento
                                    </label>
                                    <input
                                        type="text"
                                        value={data.ciudad}
                                        onChange={(event) => setData('ciudad', event.target.value)}
                                        placeholder="Ej: Guatemala"
                                        className="w-full rounded-md border border-[#262626] bg-black px-3 py-2 text-sm text-white transition-all focus:border-white focus:outline-none"
                                    />
                                    {errors.ciudad && <p className="mt-2 text-xs font-bold text-red-400">{errors.ciudad}</p>}
                                </div>
                                <div>
                                    <label className="mb-1 block text-[10px] font-bold uppercase text-[#737373]">
                                        Departamento / Estado
                                    </label>
                                    <input
                                        type="text"
                                        value={data.estado}
                                        onChange={(event) => setData('estado', event.target.value)}
                                        placeholder="Ej: Sacatepequez"
                                        className="w-full rounded-md border border-[#262626] bg-black px-3 py-2 text-sm text-white transition-all focus:border-white focus:outline-none"
                                    />
                                    {errors.estado && <p className="mt-2 text-xs font-bold text-red-400">{errors.estado}</p>}
                                </div>
                            </div>
                            <div className="mt-8 flex gap-3">
                                <button
                                    type="button"
                                    onClick={() => setOpenModal(false)}
                                    className="flex-1 rounded-md border border-[#262626] px-4 py-2 text-xs font-bold text-[#a1a1a1]"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="flex-1 rounded-md bg-white px-4 py-2 text-xs font-bold text-black hover:bg-gray-200 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    {processing ? 'Guardando...' : editUrl ? 'Actualizar' : 'Guardar'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}

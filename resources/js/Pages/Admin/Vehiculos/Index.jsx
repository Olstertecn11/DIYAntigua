import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';

const emptyVehicle = {
    id: null,
    nombre: '',
    min_pasajeros: 1,
    max_pasajeros: 1,
    activo: true,
};

export default function VehiculosIndex({ vehiculos, urls }) {
    const [openModal, setOpenModal] = useState(false);
    const [editUrl, setEditUrl] = useState(null);
    const { data, setData, post, put, processing, reset, errors } = useForm(emptyVehicle);

    const openCreate = () => {
        reset();
        setData(emptyVehicle);
        setEditUrl(null);
        setOpenModal(true);
    };

    const openEdit = (vehiculo) => {
        setData({
            id: vehiculo.id,
            nombre: vehiculo.nombre,
            min_pasajeros: vehiculo.min_pasajeros,
            max_pasajeros: vehiculo.max_pasajeros,
            activo: vehiculo.activo,
        });
        setEditUrl(vehiculo.urls.update);
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

    const destroy = (vehiculo) => {
        if (!window.confirm('Eliminar este tipo de vehiculo?')) {
            return;
        }

        router.delete(vehiculo.urls.destroy, { preserveScroll: true });
    };

    return (
        <AdminLayout>
            <Head title="Flota de Vehiculos" />

            <div className="mx-auto max-w-7xl p-8 text-white">
                <header className="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h1 className="text-3xl font-bold uppercase tracking-tight">Flota de Vehiculos</h1>
                        <p className="text-sm text-[#a1a1a1]">Administra los tipos de transporte y sus capacidades.</p>
                    </div>
                    <button
                        type="button"
                        onClick={openCreate}
                        className="inline-flex items-center justify-center rounded-md bg-white px-6 py-2 text-xs font-bold uppercase text-black shadow-lg hover:bg-gray-200"
                    >
                        <i className="fas fa-plus mr-2" />
                        Nuevo Vehiculo
                    </button>
                </header>

                <div className="overflow-hidden rounded-xl border border-[#262626] bg-[#0a0a0a] shadow-2xl">
                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[760px] text-left text-sm">
                            <thead className="border-b border-[#262626] bg-[#050505] text-[10px] uppercase tracking-widest text-[#737373]">
                                <tr>
                                    <th className="px-6 py-4">Tipo de Vehiculo</th>
                                    <th className="px-6 py-4 text-center">Capacidad</th>
                                    <th className="px-6 py-4 text-center">Estado</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#262626]">
                                {vehiculos.length > 0 ? vehiculos.map((vehiculo) => (
                                    <tr key={vehiculo.id} className="transition-colors hover:bg-[#111]">
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-4">
                                                <div className="flex h-10 w-10 items-center justify-center rounded-lg border border-[#262626] bg-[#1a1a1a]">
                                                    <i className={`fas ${vehiculo.icono || 'fa-car'} text-yellow-500`} />
                                                </div>
                                                <div>
                                                    <span className="block font-bold uppercase text-white">{vehiculo.nombre}</span>
                                                    <span className="text-[10px] text-[#555]">{vehiculo.min_pasajeros}-{vehiculo.max_pasajeros} pasajeros</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            <span className="rounded-full border border-[#262626] bg-black px-3 py-1 text-xs text-[#a1a1a1]">
                                                <i className="fas fa-users mr-1 opacity-50" />
                                                {vehiculo.min_pasajeros} - {vehiculo.max_pasajeros} pers.
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            <span className={[
                                                'rounded px-2 py-1 text-[10px] font-bold uppercase',
                                                vehiculo.activo ? 'bg-green-500/10 text-green-500' : 'bg-red-500/10 text-red-500',
                                            ].join(' ')}
                                            >
                                                {vehiculo.activo ? 'Activo' : 'Inactivo'}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <div className="flex items-center justify-end gap-2">
                                                <button
                                                    type="button"
                                                    onClick={() => openEdit(vehiculo)}
                                                    className="flex h-9 w-9 items-center justify-center rounded-lg border border-transparent text-[#737373] transition-all hover:border-[#262626] hover:bg-[#1a1a1a] hover:text-white"
                                                    title="Editar"
                                                >
                                                    <i className="fas fa-edit text-sm" />
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => destroy(vehiculo)}
                                                    className="flex h-9 w-9 items-center justify-center rounded-lg border border-transparent text-red-500/40 transition-all hover:border-red-500/20 hover:bg-red-500/10 hover:text-red-500"
                                                    title="Eliminar"
                                                >
                                                    <i className="fas fa-trash text-sm" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan="4" className="px-6 py-10 text-center text-sm font-semibold text-[#737373]">
                                            No hay vehiculos registrados.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {openModal && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4">
                    <div className="w-full max-w-md rounded-2xl border border-[#262626] bg-[#0a0a0a] p-8 shadow-2xl">
                        <h2 className="mb-6 text-xl font-bold">{editUrl ? 'Editar Vehiculo' : 'Nuevo Vehiculo'}</h2>

                        <form onSubmit={submit} className="space-y-5">
                            <TextInput label="Nombre del Tipo" value={data.nombre} error={errors.nombre} onChange={(value) => setData('nombre', value)} placeholder="Ej: SUV Premium, Microbus..." />

                            <div className="grid grid-cols-2 gap-4">
                                <NumberInput label="Min. Pasajeros" value={data.min_pasajeros} error={errors.min_pasajeros} onChange={(value) => setData('min_pasajeros', value)} />
                                <NumberInput label="Max. Pasajeros" value={data.max_pasajeros} error={errors.max_pasajeros} onChange={(value) => setData('max_pasajeros', value)} />
                            </div>

                            <div className="flex items-center gap-3 rounded-lg border border-[#262626] bg-[#050505] p-3">
                                <input
                                    type="checkbox"
                                    checked={data.activo}
                                    onChange={(event) => setData('activo', event.target.checked)}
                                    className="h-4 w-4 accent-yellow-500"
                                    id="activo"
                                />
                                <label htmlFor="activo" className="cursor-pointer text-xs font-bold uppercase text-white">Vehiculo Disponible</label>
                            </div>

                            <div className="flex gap-4 pt-4">
                                <button
                                    type="button"
                                    onClick={() => setOpenModal(false)}
                                    className="flex-1 py-3 text-[10px] font-bold uppercase text-[#a1a1a1] transition-colors hover:text-white"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="flex-1 rounded-xl bg-white py-3 text-[10px] font-bold uppercase text-black transition-all hover:bg-gray-200 disabled:cursor-not-allowed disabled:opacity-60"
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

function TextInput({ label, value, onChange, error, placeholder }) {
    return (
        <div>
            <label className="text-[10px] font-bold uppercase text-[#737373]">{label}</label>
            <input
                type="text"
                value={value}
                onChange={(event) => onChange(event.target.value)}
                required
                placeholder={placeholder}
                className="mt-1 w-full rounded-lg border border-[#262626] bg-black p-3 text-white outline-none transition-all focus:border-yellow-500"
            />
            {error && <p className="mt-2 text-xs font-bold text-red-400">{error}</p>}
        </div>
    );
}

function NumberInput({ label, value, onChange, error }) {
    return (
        <div>
            <label className="text-[10px] font-bold uppercase text-[#737373]">{label}</label>
            <input
                type="number"
                value={value}
                onChange={(event) => onChange(event.target.value)}
                required
                min="1"
                className="mt-1 w-full rounded-lg border border-[#262626] bg-black p-3 text-white outline-none"
            />
            {error && <p className="mt-2 text-xs font-bold text-red-400">{error}</p>}
        </div>
    );
}

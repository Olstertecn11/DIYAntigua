import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';

function money(value) {
    return `Q.${Number(value || 0).toLocaleString('es-GT', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

export default function RutasIndex({ rutas, lugares, vehiculos, urls }) {
    const [openModal, setOpenModal] = useState(false);
    const [selectedVehiculo, setSelectedVehiculo] = useState('');
    const { data, setData, post, processing, reset, errors } = useForm({
        origen_id: lugares[0]?.id || '',
        destino_id: lugares[1]?.id || lugares[0]?.id || '',
        kilometraje: '',
        vehiculos: [],
    });

    const addVehiculo = () => {
        if (!selectedVehiculo) {
            return;
        }

        const vehiculo = vehiculos.find((item) => String(item.id) === String(selectedVehiculo));
        if (!vehiculo || data.vehiculos.some((item) => String(item.id) === String(vehiculo.id))) {
            return;
        }

        setData('vehiculos', [...data.vehiculos, { id: vehiculo.id, nombre: vehiculo.nombre, precio: 0 }]);
        setSelectedVehiculo('');
    };

    const removeVehiculo = (index) => {
        setData('vehiculos', data.vehiculos.filter((_, currentIndex) => currentIndex !== index));
    };

    const updatePrecio = (index, precio) => {
        setData('vehiculos', data.vehiculos.map((item, currentIndex) => (
            currentIndex === index ? { ...item, precio } : item
        )));
    };

    const submit = (event) => {
        event.preventDefault();

        post(urls.store, {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setData({
                    origen_id: lugares[0]?.id || '',
                    destino_id: lugares[1]?.id || lugares[0]?.id || '',
                    kilometraje: '',
                    vehiculos: [],
                });
                setSelectedVehiculo('');
                setOpenModal(false);
            },
        });
    };

    const destroy = (ruta) => {
        if (!window.confirm('Eliminar ruta?')) {
            return;
        }

        router.delete(ruta.urls.destroy, { preserveScroll: true });
    };

    return (
        <AdminLayout>
            <Head title="Tarifario Operativo" />

            <div className="mx-auto max-w-7xl p-8 text-white">
                <header className="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h1 className="text-3xl font-bold uppercase">Tarifario Operativo</h1>
                        <p className="text-sm text-[#a1a1a1]">Gestion dinamica de rutas por tipo de vehiculo.</p>
                    </div>
                    <button
                        type="button"
                        onClick={() => setOpenModal(true)}
                        className="inline-flex items-center justify-center rounded-md bg-white px-6 py-2 text-xs font-bold text-black shadow-lg hover:bg-gray-200"
                    >
                        <i className="fas fa-plus mr-2" />
                        NUEVA RUTA
                    </button>
                </header>

                <div className="overflow-hidden rounded-xl border border-[#262626] bg-[#0a0a0a] shadow-2xl">
                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[820px] text-left text-sm">
                            <thead className="border-b border-[#262626] bg-[#050505] text-[10px] uppercase tracking-widest text-[#737373]">
                                <tr>
                                    <th className="px-6 py-4">Ruta (Origen a Destino)</th>
                                    <th className="px-6 py-4">Vehiculos Disponibles y Precios</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#262626]">
                                {rutas.length > 0 ? rutas.map((ruta) => (
                                    <tr key={ruta.id} className="transition-colors hover:bg-[#111]">
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-3">
                                                <span className="font-bold text-white">{ruta.origen?.nombre || 'N/A'}</span>
                                                <i className="fas fa-arrow-right text-[10px] text-yellow-500" />
                                                <span className="font-bold text-white">{ruta.destino?.nombre || 'N/A'}</span>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex flex-wrap gap-2">
                                                {ruta.vehiculos.map((vehiculo) => (
                                                    <span key={vehiculo.id} className="rounded-full border border-[#262626] bg-[#1a1a1a] px-3 py-1 font-mono text-[11px] text-green-500">
                                                        <strong className="text-yellow-500">{vehiculo.nombre}:</strong> {money(vehiculo.precio_tarifa)}
                                                    </span>
                                                ))}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <button
                                                type="button"
                                                onClick={() => destroy(ruta)}
                                                className="text-red-500/40 transition-colors hover:text-red-500"
                                                title="Eliminar"
                                            >
                                                <i className="fas fa-trash" />
                                            </button>
                                        </td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan="3" className="px-6 py-10 text-center text-sm font-semibold text-[#737373]">
                                            No hay rutas configuradas.
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
                    <div className="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-2xl border border-[#262626] bg-[#0a0a0a] p-8">
                        <h2 className="mb-6 text-xl font-bold">Configurar Nueva Ruta y Vehiculos</h2>

                        <form onSubmit={submit} className="space-y-6">
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <SelectField label="Lugar de Origen" value={data.origen_id} error={errors.origen_id} onChange={(value) => setData('origen_id', value)} options={lugares} />
                                <SelectField label="Lugar de Destino" value={data.destino_id} error={errors.destino_id} onChange={(value) => setData('destino_id', value)} options={lugares} />
                            </div>

                            <div className="rounded-xl border border-[#262626] bg-[#050505] p-4">
                                <label className="mb-2 block text-[10px] font-bold uppercase text-yellow-500">Asignar Vehiculo a esta Ruta</label>
                                <div className="flex flex-col gap-2 sm:flex-row">
                                    <select
                                        value={selectedVehiculo}
                                        onChange={(event) => setSelectedVehiculo(event.target.value)}
                                        className="flex-1 rounded-lg border border-[#262626] bg-black p-2 text-white outline-none"
                                    >
                                        <option value="">Selecciona un tipo de vehiculo...</option>
                                        {vehiculos.map((vehiculo) => (
                                            <option key={vehiculo.id} value={vehiculo.id}>{vehiculo.nombre} (Cap: {vehiculo.max_pasajeros})</option>
                                        ))}
                                    </select>
                                    <button
                                        type="button"
                                        onClick={addVehiculo}
                                        className="rounded-lg bg-yellow-600 px-4 py-2 text-xs font-bold uppercase text-black hover:bg-yellow-500"
                                    >
                                        Agregar
                                    </button>
                                </div>

                                <div className="mt-4">
                                    {data.vehiculos.length > 0 ? (
                                        <table className="w-full text-left text-xs">
                                            <thead>
                                                <tr className="border-b border-[#262626] text-[#737373]">
                                                    <th className="py-2">Vehiculo</th>
                                                    <th className="py-2">Tarifa (Q)</th>
                                                    <th className="py-2 text-right" />
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {data.vehiculos.map((item, index) => (
                                                    <tr key={item.id} className="border-b border-[#1a1a1a]">
                                                        <td className="py-3 font-bold text-white">{item.nombre}</td>
                                                        <td className="py-3">
                                                            <input
                                                                type="number"
                                                                step="0.01"
                                                                value={item.precio}
                                                                onChange={(event) => updatePrecio(index, event.target.value)}
                                                                required
                                                                className="w-32 rounded border border-[#262626] bg-black px-2 py-1 font-mono text-green-500 outline-none focus:border-green-500"
                                                            />
                                                        </td>
                                                        <td className="py-3 text-right">
                                                            <button type="button" onClick={() => removeVehiculo(index)} className="text-red-500 hover:text-red-400">
                                                                <i className="fas fa-trash text-sm" />
                                                            </button>
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    ) : (
                                        <p className="py-4 text-center text-[10px] italic text-[#4a4a4a]">No has agregado vehiculos a esta ruta aun.</p>
                                    )}
                                    {errors.vehiculos && <p className="mt-3 text-xs font-bold text-red-400">{errors.vehiculos}</p>}
                                </div>
                            </div>

                            <div className="flex gap-4 pt-4">
                                <button
                                    type="button"
                                    onClick={() => {
                                        setOpenModal(false);
                                        setData('vehiculos', []);
                                    }}
                                    className="flex-1 py-3 text-xs font-bold uppercase text-[#a1a1a1] transition-colors hover:text-white"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="flex-1 rounded-xl bg-white py-3 text-xs font-bold uppercase text-black transition-all hover:bg-gray-200 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    {processing ? 'Guardando...' : 'Guardar Ruta Completa'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}

function SelectField({ label, value, onChange, options, error }) {
    return (
        <div>
            <label className="text-[10px] font-bold uppercase text-[#737373]">{label}</label>
            <select
                value={value}
                onChange={(event) => onChange(event.target.value)}
                required
                className="mt-1 w-full rounded-lg border border-[#262626] bg-black p-3 text-white outline-none focus:border-white"
            >
                {options.map((option) => (
                    <option key={option.id} value={option.id}>{option.nombre}</option>
                ))}
            </select>
            {error && <p className="mt-2 text-xs font-bold text-red-400">{error}</p>}
        </div>
    );
}

import { Head, router, useForm } from '@inertiajs/react';
import { useMemo, useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';

export default function UsuariosIndex({ users, roles = [], filters = {}, urls = {} }) {
    const [openModal, setOpenModal] = useState(false);
    const [editUrl, setEditUrl] = useState(null);
    const [search, setSearch] = useState(filters.search || '');
    const [roleFilter, setRoleFilter] = useState(filters.role || '');
    const { data, setData, post, put, processing, reset, errors, clearErrors } = useForm({
        name: '',
        email: '',
        telefono: '',
        direccion: '',
        role: roles[0]?.slug || 'cliente',
    });

    const userRows = users?.data || [];
    const totalUsers = users?.total || userRows.length;
    const adminCount = useMemo(
        () => userRows.filter((user) => user.roles.some((role) => role.slug === 'admin')).length,
        [userRows],
    );

    const applyFilters = (event) => {
        event.preventDefault();
        router.get(urls.index, { search, role: roleFilter }, { preserveState: true, preserveScroll: true });
    };

    const clearFilters = () => {
        setSearch('');
        setRoleFilter('');
        router.get(urls.index, {}, { preserveState: true, preserveScroll: true });
    };

    const openCreate = () => {
        reset();
        clearErrors();
        setData({
            name: '',
            email: '',
            telefono: '',
            direccion: '',
            role: roles[0]?.slug || 'cliente',
        });
        setEditUrl(null);
        setOpenModal(true);
    };

    const openEdit = (user) => {
        clearErrors();
        setData({
            name: user.name || '',
            email: user.email || '',
            telefono: user.telefono || '',
            direccion: user.direccion || '',
            role: user.primary_role || roles[0]?.slug || 'cliente',
        });
        setEditUrl(user.urls.update);
        setOpenModal(true);
    };

    const submit = (event) => {
        event.preventDefault();

        const options = {
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setEditUrl(null);
                setOpenModal(false);
            },
        };

        if (editUrl) {
            put(editUrl, options);
        } else {
            post(urls.store, options);
        }
    };

    const resendInvitation = (user) => {
        router.post(user.urls.invite, {}, { preserveScroll: true });
    };

    return (
        <AdminLayout>
            <Head title="Usuarios" />

            <div className="mx-auto max-w-7xl p-8">
                <header className="mb-8 flex flex-col justify-between gap-4 md:flex-row md:items-center">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight text-white">Usuarios</h1>
                        <p className="text-sm text-[#a1a1a1]">Alta, roles e invitaciones de acceso.</p>
                    </div>
                    <button
                        type="button"
                        onClick={openCreate}
                        className="inline-flex items-center justify-center rounded-md bg-white px-6 py-2 text-xs font-bold text-black shadow-[0_0_20px_rgba(255,255,255,0.1)] transition-all hover:bg-gray-200"
                    >
                        <i className="fas fa-user-plus mr-2" />
                        Nuevo Usuario
                    </button>
                </header>

                <section className="mb-6 grid gap-4 md:grid-cols-3">
                    <Stat label="Usuarios" value={totalUsers} icon="fa-users" />
                    <Stat label="Administradores" value={adminCount} icon="fa-user-shield" />
                    <Stat label="Roles activos" value={roles.length} icon="fa-id-badge" />
                </section>

                <form onSubmit={applyFilters} className="mb-6 flex flex-col gap-3 rounded-xl border border-[#262626] bg-[#0a0a0a] p-4 md:flex-row md:items-end">
                    <div className="flex-1">
                        <Label>Buscar</Label>
                        <div className="relative">
                            <i className="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-xs text-[#737373]" />
                            <input
                                type="search"
                                value={search}
                                onChange={(event) => setSearch(event.target.value)}
                                placeholder="Nombre, correo o telefono"
                                className="w-full rounded-md border border-[#262626] bg-black px-9 py-2 text-sm text-white outline-none transition focus:border-white"
                            />
                        </div>
                    </div>
                    <div className="md:w-56">
                        <Label>Rol</Label>
                        <select
                            value={roleFilter}
                            onChange={(event) => setRoleFilter(event.target.value)}
                            className="w-full rounded-md border border-[#262626] bg-black px-3 py-2 text-sm text-white outline-none transition focus:border-white"
                        >
                            <option value="">Todos</option>
                            {roles.map((role) => (
                                <option key={role.slug} value={role.slug}>{role.name}</option>
                            ))}
                        </select>
                    </div>
                    <div className="flex gap-2">
                        <button type="submit" className="h-10 rounded-md bg-white px-4 text-xs font-bold text-black transition hover:bg-gray-200">
                            <i className="fas fa-filter mr-2" />
                            Filtrar
                        </button>
                        <button type="button" onClick={clearFilters} className="h-10 rounded-md border border-[#262626] px-4 text-xs font-bold text-[#a1a1a1] transition hover:bg-[#111] hover:text-white">
                            <i className="fas fa-rotate-left" />
                        </button>
                    </div>
                </form>

                <div className="overflow-hidden rounded-xl border border-[#262626] bg-[#0a0a0a] shadow-2xl">
                    <div className="overflow-x-auto">
                        <table className="w-full min-w-[920px] text-left text-sm">
                            <thead className="border-b border-[#262626] bg-[#050505] text-[10px] uppercase tracking-widest text-[#737373]">
                                <tr>
                                    <th className="px-6 py-4">Usuario</th>
                                    <th className="px-6 py-4">Rol</th>
                                    <th className="px-6 py-4">Contacto</th>
                                    <th className="px-6 py-4">Reservas</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#262626]">
                                {userRows.length > 0 ? userRows.map((user) => (
                                    <tr key={user.id} className="transition-colors hover:bg-[#111]">
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-3">
                                                <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[#262626] bg-black text-sm font-black text-white">
                                                    {user.name?.charAt(0)}
                                                </div>
                                                <div>
                                                    <div className="font-bold text-white">{user.name}</div>
                                                    <div className="text-[10px] font-semibold uppercase tracking-widest text-[#737373]">Alta {user.created_at}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex flex-wrap gap-2">
                                                {user.roles.map((role) => (
                                                    <span key={role.slug} className={roleBadge(role.slug)}>
                                                        {role.name}
                                                    </span>
                                                ))}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="font-medium text-[#d4d4d4]">{user.email}</div>
                                            <div className="text-[10px] text-[#737373]">{user.telefono || 'Sin telefono'}</div>
                                        </td>
                                        <td className="px-6 py-4 text-[#a1a1a1]">{user.reservaciones_count}</td>
                                        <td className="px-6 py-4 text-right">
                                            <div className="flex justify-end gap-3">
                                                <button
                                                    type="button"
                                                    onClick={() => openEdit(user)}
                                                    className="text-xs font-bold uppercase text-white/60 transition-colors hover:text-white"
                                                >
                                                    Editar
                                                </button>
                                                <button
                                                    type="button"
                                                    onClick={() => resendInvitation(user)}
                                                    className="text-xs font-bold uppercase text-yellow-400/70 transition-colors hover:text-yellow-300"
                                                >
                                                    Reenviar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan="5" className="px-6 py-10 text-center text-sm font-semibold text-[#737373]">
                                            No hay usuarios para mostrar.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                {users?.links?.length > 3 && (
                    <div className="mt-6 flex flex-wrap justify-end gap-2">
                        {users.links.map((link, index) => (
                            <button
                                key={`${link.label}-${index}`}
                                type="button"
                                disabled={!link.url}
                                onClick={() => link.url && router.visit(link.url, { preserveScroll: true })}
                                className={[
                                    'h-9 min-w-9 rounded-md border px-3 text-xs font-bold transition',
                                    link.active ? 'border-white bg-white text-black' : 'border-[#262626] text-[#a1a1a1] hover:bg-[#111] hover:text-white',
                                    !link.url ? 'cursor-not-allowed opacity-40' : '',
                                ].join(' ')}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ))}
                    </div>
                )}
            </div>

            {openModal && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm">
                    <div className="w-full max-w-2xl overflow-hidden rounded-xl border border-[#262626] bg-[#0a0a0a]">
                        <div className="p-8">
                            <div className="mb-6 flex items-center justify-between gap-4">
                                <h2 className="text-xl font-bold text-white">{editUrl ? 'Editar usuario' : 'Crear usuario'}</h2>
                                <button
                                    type="button"
                                    onClick={() => setOpenModal(false)}
                                    className="flex h-8 w-8 items-center justify-center rounded-full bg-white/10 text-xs text-white/70 transition hover:bg-white/20 hover:text-white"
                                    aria-label="Cerrar"
                                >
                                    <i className="fas fa-times" />
                                </button>
                            </div>
                            <form onSubmit={submit}>
                                <div className="grid gap-4 sm:grid-cols-2">
                                    <Input label="Nombre" value={data.name} error={errors.name} onChange={(value) => setData('name', value)} required />
                                    <Input label="Correo" type="email" value={data.email} error={errors.email} onChange={(value) => setData('email', value)} required />
                                    <Input label="Telefono" value={data.telefono} error={errors.telefono} onChange={(value) => setData('telefono', value)} />
                                    <Input label="Direccion" value={data.direccion} error={errors.direccion} onChange={(value) => setData('direccion', value)} />
                                </div>

                                <div className="mt-5">
                                    <Label>Rol</Label>
                                    <div className="grid gap-2 sm:grid-cols-2">
                                        {roles.map((role) => (
                                            <button
                                                key={role.slug}
                                                type="button"
                                                onClick={() => setData('role', role.slug)}
                                                className={[
                                                    'rounded-md border px-4 py-3 text-left transition',
                                                    data.role === role.slug
                                                        ? 'border-white bg-white text-black'
                                                        : 'border-[#262626] bg-black text-white hover:border-white/50',
                                                ].join(' ')}
                                            >
                                                <span className="block text-sm font-black">{role.name}</span>
                                                <span className="mt-1 block text-[10px] font-bold uppercase tracking-widest opacity-60">{role.slug}</span>
                                            </button>
                                        ))}
                                    </div>
                                    {errors.role && <p className="mt-2 text-xs font-bold text-red-400">{errors.role}</p>}
                                </div>

                                {!editUrl && (
                                    <div className="mt-5 rounded-md border border-yellow-400/20 bg-yellow-400/10 px-4 py-3 text-xs font-semibold leading-relaxed text-yellow-100">
                                        <i className="fas fa-envelope-circle-check mr-2 text-yellow-300" />
                                        El usuario recibira un enlace para crear su propia contrasena.
                                    </div>
                                )}

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
                                        {processing ? 'Guardando...' : editUrl ? 'Actualizar' : 'Crear e invitar'}
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

function Stat({ label, value, icon }) {
    return (
        <div className="rounded-xl border border-[#262626] bg-[#0a0a0a] p-5">
            <div className="mb-4 flex h-9 w-9 items-center justify-center rounded-md bg-white text-sm text-black">
                <i className={`fas ${icon}`} />
            </div>
            <div className="text-2xl font-black text-white">{value}</div>
            <div className="text-[10px] font-bold uppercase tracking-widest text-[#737373]">{label}</div>
        </div>
    );
}

function Label({ children }) {
    return <label className="mb-1 block text-[10px] font-bold uppercase tracking-widest text-[#737373]">{children}</label>;
}

function Input({ label, value, onChange, error, type = 'text', required = false }) {
    return (
        <div>
            <Label>{label}</Label>
            <input
                type={type}
                value={value}
                onChange={(event) => onChange(event.target.value)}
                required={required}
                className="w-full rounded-md border border-[#262626] bg-black px-3 py-2 text-sm text-white transition-all focus:border-white focus:outline-none"
            />
            {error && <p className="mt-2 text-xs font-bold text-red-400">{error}</p>}
        </div>
    );
}

function roleBadge(slug) {
    const base = 'rounded-full px-2 py-0.5 text-[9px] font-bold uppercase';

    if (slug === 'admin') {
        return `${base} bg-yellow-500/10 text-yellow-400`;
    }

    return `${base} bg-blue-500/10 text-blue-400`;
}

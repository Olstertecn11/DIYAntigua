import { Head, Link, useForm } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import { useMemo } from 'react';

export default function ProfileEdit({ user, countries, urls }) {
    const profile = useForm({
        name: user.name || '',
        email: user.email || '',
        telefono_country_code: user.telefono_country_code || 'GT',
        telefono_national: user.telefono_national || '',
        direccion: user.direccion || '',
        avatar_base64: user.avatar_base64 || '',
    });
    const avatarPreview = profile.data.avatar_base64 || '';
    const initials = useMemo(() => String(profile.data.name || profile.data.email || 'U').trim().slice(0, 1).toUpperCase(), [profile.data.email, profile.data.name]);

    return (
        <PublicLayout>
            <Head title="Configuracion de perfil" />
            <main className="min-h-screen bg-[linear-gradient(90deg,#FCCA00_0_10px,transparent_10px),linear-gradient(135deg,#fff_0%,#f7f7f2_58%,#ecebe5_100%)] pb-12 pt-36">
                <div className="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                    <div className="soft-rise mb-8 rounded-[2rem] border border-black/10 bg-white/85 p-6 shadow-[0_24px_70px_rgba(0,0,0,.08)] backdrop-blur">
                        <div className="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
                            <div>
                                <span className="inline-flex items-center gap-2 rounded-full border border-[#fcca00]/40 bg-white px-4 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-[#363636]"><i className="fas fa-user-gear text-[#fcca00]" /> Mi cuenta</span>
                                <h1 className="mt-4 text-3xl font-black text-[#111111] md:text-4xl">Configuracion de perfil</h1>
                                <p className="mt-2 text-sm text-[#666666]">Manten tus datos listos para reservar mas rapido y recibir tus comprobantes sin errores.</p>
                            </div>
                            <div className="flex items-center gap-4 rounded-3xl border border-black/10 bg-white p-3 shadow-sm">
                                {avatarPreview ? (
                                    <img src={avatarPreview} alt={profile.data.name || 'Usuario'} className="h-16 w-16 rounded-2xl object-cover" />
                                ) : (
                                    <span className="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#111111] text-2xl font-black text-[#fcca00]">{initials}</span>
                                )}
                                <div>
                                    <p className="mb-0 text-sm font-black text-black">{profile.data.name || 'Usuario'}</p>
                                    <p className="mb-0 text-xs font-bold text-[#777]">{profile.data.email || 'Correo pendiente'}</p>
                                </div>
                            </div>
                        </div>
                        <div className="mt-5 grid gap-3 md:grid-cols-3">
                            <AccountPill icon="fa-envelope" label="Correo" value={profile.data.email || 'Pendiente'} />
                            <AccountPill icon="fa-phone" label="Telefono" value={profile.data.telefono_national || 'Pendiente'} />
                            <AccountPill icon="fa-location-dot" label="Direccion" value={profile.data.direccion || 'Pendiente'} />
                        </div>
                    </div>

                    <div className="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                        <section className="soft-rise rounded-[1.75rem] border border-black/10 bg-white p-6 shadow-[0_24px_70px_rgba(0,0,0,.08)]">
                            <h2 className="text-xl font-black text-[#111111]">Datos personales</h2>
                            <form onSubmit={(event) => { event.preventDefault(); profile.put(urls.update, { preserveScroll: true }); }} className="mt-6 grid gap-5">
                                <AvatarInput
                                    value={profile.data.avatar_base64}
                                    name={profile.data.name}
                                    initials={initials}
                                    error={profile.errors.avatar_base64}
                                    onChange={(value) => profile.setData('avatar_base64', value)}
                                />
                                <TextInput label="Nombre completo" value={profile.data.name} error={profile.errors.name} onChange={(value) => profile.setData('name', value)} />
                                <div className="grid gap-5 md:grid-cols-2">
                                    <TextInput label="Correo" type="email" value={profile.data.email} error={profile.errors.email} onChange={(value) => profile.setData('email', value)} />
                                    <PhoneInput label="Telefono" countries={countries} country={profile.data.telefono_country_code} number={profile.data.telefono_national} error={profile.errors.telefono_country_code || profile.errors.telefono_national} setCountry={(value) => profile.setData('telefono_country_code', value)} setNumber={(value) => profile.setData('telefono_national', value)} />
                                </div>
                                <TextInput label="Direccion frecuente" value={profile.data.direccion} error={profile.errors.direccion} onChange={(value) => profile.setData('direccion', value)} required={false} />
                                <button disabled={profile.processing} className="inline-flex items-center justify-center gap-2 rounded-full bg-[#fcca00] px-6 py-3 text-sm font-black text-black shadow-[0_14px_34px_rgba(252,202,0,.28)] transition hover:bg-[#e8ba00] disabled:opacity-60">{profile.processing ? <i className="fas fa-circle-notch animate-spin" /> : <i className="fas fa-check" />} Guardar cambios</button>
                            </form>
                        </section>

                        <section className="soft-rise relative overflow-hidden rounded-[1.75rem] border border-black/10 bg-[#111111] p-6 text-white shadow-[0_24px_70px_rgba(0,0,0,.12)]">
                            <div className="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-[#FCCA00]/20 blur-3xl" />
                            <div className="relative">
                                <span className="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FCCA00] text-black"><i className="fas fa-shield-halved" /></span>
                                <h2 className="mt-6 text-2xl font-black">Seguridad de tu cuenta</h2>
                                <p className="mt-3 text-sm leading-7 text-white/55">El cambio de contraseña ahora incluye verificación por correo para proteger mejor tu cuenta.</p>
                                <Link href={urls.security} className="mt-7 inline-flex w-full items-center justify-center gap-2 rounded-full border border-[#FCCA00]/70 px-6 py-3 text-sm font-black text-[#FCCA00] no-underline transition hover:bg-[#FCCA00] hover:text-black">
                                    Cambiar contraseña <i className="fas fa-arrow-right" />
                                </Link>
                            </div>
                        </section>
                    </div>
                </div>
            </main>
        </PublicLayout>
    );
}

function AccountPill({ icon, label, value }) {
    return (
        <div className="rounded-2xl border border-black/10 bg-white p-4">
            <div className="flex items-center gap-3">
                <span className="flex h-10 w-10 items-center justify-center rounded-xl bg-[#FCCA00]/25 text-[#8b6c00]">
                    <i className={`fas ${icon}`} />
                </span>
                <div className="min-w-0">
                    <span className="block text-[10px] font-black uppercase tracking-widest text-[#777]">{label}</span>
                    <strong className="block truncate text-sm text-black">{value}</strong>
                </div>
            </div>
        </div>
    );
}

function TextInput({ label, value, onChange, error, type = 'text', required = true }) {
    return <div><label className="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">{label}</label><input type={type} value={value} onChange={(event) => onChange(event.target.value)} required={required} className="mt-2 w-full rounded-2xl border border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold outline-none focus:border-[#fcca00] focus:ring-4 focus:ring-[#fcca00]/20" />{error && <p className="mt-1 text-xs font-semibold text-red-600">{error}</p>}</div>;
}

function AvatarInput({ value, name, initials, error, onChange }) {
    const handleFile = (event) => {
        const file = event.target.files?.[0];

        if (!file) {
            return;
        }

        if (!['image/png', 'image/jpeg', 'image/webp', 'image/gif'].includes(file.type) || file.size > 420 * 1024) {
            event.target.value = '';
            onChange(value);
            window.alert('Usa una imagen PNG, JPG, WEBP o GIF menor a 420 KB.');
            return;
        }

        const reader = new FileReader();
        reader.onload = () => onChange(String(reader.result || ''));
        reader.readAsDataURL(file);
    };

    return (
        <div className="rounded-3xl border border-black/10 bg-[#fbfaf7] p-4">
            <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div className="flex items-center gap-4">
                    {value ? (
                        <img src={value} alt={name || 'Foto de perfil'} className="h-20 w-20 rounded-3xl object-cover shadow-sm" />
                    ) : (
                        <span className="flex h-20 w-20 items-center justify-center rounded-3xl bg-black text-3xl font-black text-[#fcca00]">{initials}</span>
                    )}
                    <div>
                        <p className="mb-1 text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">Foto de perfil</p>
                        <p className="mb-0 max-w-md text-sm font-semibold text-[#777]">PNG, JPG, WEBP o GIF. Se guarda como base64 en la base de datos.</p>
                    </div>
                </div>
                <div className="flex flex-wrap gap-2">
                    <label className="inline-flex cursor-pointer items-center justify-center gap-2 rounded-full bg-black px-5 py-3 text-sm font-black text-white transition hover:bg-[#242424]">
                        <i className="fas fa-camera text-[#fcca00]" />
                        Subir foto
                        <input type="file" accept="image/png,image/jpeg,image/webp,image/gif" onChange={handleFile} className="sr-only" />
                    </label>
                    {value && (
                        <button type="button" onClick={() => onChange('')} className="rounded-full border border-black/10 px-5 py-3 text-sm font-black text-[#555] transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">
                            Quitar
                        </button>
                    )}
                </div>
            </div>
            {error && <p className="mt-2 text-xs font-semibold text-red-600">{error}</p>}
        </div>
    );
}

function PhoneInput({ label, countries, country, number, error, setCountry, setNumber }) {
    const selected = countries[country] || {};

    return (
        <div>
            <label className="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">{label}</label>
            <div className={[
                'mt-2 grid min-w-0 overflow-hidden rounded-2xl border bg-[#fbfaf7] shadow-sm transition focus-within:border-[#fcca00] focus-within:ring-4 focus-within:ring-[#fcca00]/20 xl:grid-cols-[150px_minmax(0,1fr)]',
                error ? 'border-red-300' : 'border-black/10',
            ].join(' ')}
            >
                <label className="flex min-w-0 items-center gap-2 border-b border-black/10 px-4 py-3 xl:border-b-0 xl:border-r">
                    <i className="fas fa-globe text-[#b08a00]" />
                    <select value={country} onChange={(event) => setCountry(event.target.value)} className="min-w-0 flex-1 bg-transparent text-sm font-black text-[#111] outline-none">
                        {Object.entries(countries).map(([code, data]) => (
                            <option key={code} value={code}>{data.dial} {code}</option>
                        ))}
                    </select>
                </label>
                <div className="flex min-w-0 items-center gap-2 px-4 py-3">
                    <span className="shrink-0 text-sm font-black text-[#777]">{selected.dial || ''}</span>
                    <input
                        value={number}
                        inputMode="tel"
                        autoComplete="tel-national"
                        placeholder="Numero de telefono"
                        onChange={(event) => setNumber(event.target.value.replace(/[^\d\s().-]/g, ''))}
                        className="w-full min-w-0 flex-1 bg-transparent text-sm font-semibold outline-none placeholder:text-[#999]"
                    />
                </div>
            </div>
            <div className="mt-2 flex items-center justify-between gap-3 text-xs">
                <span className="font-semibold text-[#777]">{selected.name ? `Pais: ${selected.name}` : 'Selecciona el pais'}</span>
                {error && <span className="font-semibold text-red-600">{error}</span>}
            </div>
        </div>
    );
}

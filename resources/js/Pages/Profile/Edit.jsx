import { Head, useForm } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function ProfileEdit({ user, countries, urls }) {
    const profile = useForm({
        name: user.name || '',
        email: user.email || '',
        telefono_country_code: user.telefono_country_code || 'GT',
        telefono_national: user.telefono_national || '',
        direccion: user.direccion || '',
    });
    const password = useForm({ current_password: '', password: '', password_confirmation: '' });

    return (
        <PublicLayout>
            <Head title="Configuracion de perfil" />
            <main className="min-h-screen bg-[#f7f6f1] pb-12 pt-36">
                <div className="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                    <div className="mb-8">
                        <span className="inline-flex items-center gap-2 rounded-full border border-[#fcca00]/40 bg-white px-4 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-[#363636]"><i className="fas fa-user-gear text-[#fcca00]" /> Mi cuenta</span>
                        <h1 className="mt-4 text-3xl font-black text-[#111111] md:text-4xl">Configuracion de perfil</h1>
                        <p className="mt-2 text-sm text-[#666666]">Manten tus datos listos para reservar mas rapido y recibir tus comprobantes sin errores.</p>
                    </div>

                    <div className="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                        <section className="rounded-[1.75rem] border border-black/10 bg-white p-6 shadow-sm">
                            <h2 className="text-xl font-black text-[#111111]">Datos personales</h2>
                            <form onSubmit={(event) => { event.preventDefault(); profile.put(urls.update, { preserveScroll: true }); }} className="mt-6 grid gap-5">
                                <TextInput label="Nombre completo" value={profile.data.name} error={profile.errors.name} onChange={(value) => profile.setData('name', value)} />
                                <div className="grid gap-5 md:grid-cols-2">
                                    <TextInput label="Correo" type="email" value={profile.data.email} error={profile.errors.email} onChange={(value) => profile.setData('email', value)} />
                                    <PhoneInput label="Telefono" countries={countries} country={profile.data.telefono_country_code} number={profile.data.telefono_national} setCountry={(value) => profile.setData('telefono_country_code', value)} setNumber={(value) => profile.setData('telefono_national', value)} />
                                </div>
                                <TextInput label="Direccion frecuente" value={profile.data.direccion} error={profile.errors.direccion} onChange={(value) => profile.setData('direccion', value)} required={false} />
                                <button disabled={profile.processing} className="inline-flex items-center justify-center gap-2 rounded-full bg-[#fcca00] px-6 py-3 text-sm font-black text-black transition hover:bg-[#e8ba00] disabled:opacity-60"><i className="fas fa-check" /> Guardar cambios</button>
                            </form>
                        </section>

                        <section className="rounded-[1.75rem] border border-black/10 bg-[#111111] p-6 text-white shadow-sm">
                            <h2 className="text-xl font-black">Seguridad</h2>
                            <p className="mt-2 text-sm text-white/60">Cambia tu contrasena periodicamente y usa una combinacion segura.</p>
                            <form onSubmit={(event) => { event.preventDefault(); password.put(urls.password, { preserveScroll: true, onSuccess: () => password.reset() }); }} className="mt-6 grid gap-4">
                                <PasswordInput placeholder="Contrasena actual" value={password.data.current_password} error={password.errors.current_password} onChange={(value) => password.setData('current_password', value)} />
                                <PasswordInput placeholder="Nueva contrasena" value={password.data.password} error={password.errors.password} onChange={(value) => password.setData('password', value)} />
                                <PasswordInput placeholder="Confirmar contrasena" value={password.data.password_confirmation} onChange={(value) => password.setData('password_confirmation', value)} />
                                <button disabled={password.processing} className="rounded-full border border-[#fcca00]/70 px-6 py-3 text-sm font-black text-[#fcca00] transition hover:bg-[#fcca00] hover:text-black disabled:opacity-60">Actualizar contrasena</button>
                            </form>
                        </section>
                    </div>
                </div>
            </main>
        </PublicLayout>
    );
}

function TextInput({ label, value, onChange, error, type = 'text', required = true }) {
    return <div><label className="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">{label}</label><input type={type} value={value} onChange={(event) => onChange(event.target.value)} required={required} className="mt-2 w-full rounded-2xl border border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold outline-none focus:border-[#fcca00] focus:ring-4 focus:ring-[#fcca00]/20" />{error && <p className="mt-1 text-xs font-semibold text-red-600">{error}</p>}</div>;
}

function PasswordInput({ placeholder, value, onChange, error }) {
    return <div><input type="password" value={value} onChange={(event) => onChange(event.target.value)} required placeholder={placeholder} className="w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]" />{error && <p className="text-xs font-semibold text-red-300">{error}</p>}</div>;
}

function PhoneInput({ label, countries, country, number, setCountry, setNumber }) {
    return <div><label className="block text-[11px] font-black uppercase tracking-[0.16em] text-[#363636]">{label}</label><div className="mt-2 grid grid-cols-[110px_1fr] gap-2"><select value={country} onChange={(event) => setCountry(event.target.value)} className="rounded-2xl border border-black/10 bg-[#fbfaf7] px-3 py-3 text-sm font-semibold outline-none focus:border-[#fcca00]">{Object.entries(countries).map(([code, data]) => <option key={code} value={code}>{code} {data.dial}</option>)}</select><input value={number} onChange={(event) => setNumber(event.target.value)} className="rounded-2xl border border-black/10 bg-[#fbfaf7] px-4 py-3 text-sm font-semibold outline-none focus:border-[#fcca00]" /></div></div>;
}

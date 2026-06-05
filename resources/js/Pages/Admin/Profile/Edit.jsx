import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';

export default function ProfileEdit({ user, countries, urls }) {
    const profile = useForm({
        name: user.name || '',
        email: user.email || '',
        telefono_country_code: user.telefono_country_code || 'GT',
        telefono_national: user.telefono_national || '',
        direccion: user.direccion || '',
        nombre_comercial: user.afiliadoInfo?.nombre_comercial || '',
        nit: user.afiliadoInfo?.nit || '',
        telefono_negocio_country_code: user.afiliadoInfo?.telefono_negocio_country_code || 'GT',
        telefono_negocio_national: user.afiliadoInfo?.telefono_negocio_national || '',
        direccion_negocio: user.afiliadoInfo?.direccion || '',
        metodo_pago: user.afiliadoInfo?.metodo_pago || '',
        titular_pago: user.afiliadoInfo?.titular_pago || '',
        cuenta_pago: user.afiliadoInfo?.cuenta_pago || '',
    });
    const password = useForm({ current_password: '', password: '', password_confirmation: '' });

    return (
        <AdminLayout>
            <Head title="Configuracion de perfil" />
            <div className="min-h-screen bg-black p-6 text-white md:p-8">
                <div className="mb-8"><p className="text-[10px] font-black uppercase tracking-[0.24em] text-[#fcca00]">Cuenta</p><h1 className="mt-2 text-3xl font-black tracking-tight">Configuracion de perfil</h1><p className="mt-2 text-sm text-[#a1a1a1]">Gestiona tus datos de acceso, contacto y configuracion operativa.</p></div>
                <div className="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
                    <section className="rounded-2xl border border-white/10 bg-[#0a0a0a] p-6">
                        <h2 className="text-lg font-black">Datos principales</h2>
                        <form onSubmit={(event) => { event.preventDefault(); profile.put(urls.update, { preserveScroll: true }); }} className="mt-6 grid gap-5">
                            <div className="grid gap-5 md:grid-cols-2">
                                <TextInput label="Nombre" value={profile.data.name} error={profile.errors.name} onChange={(value) => profile.setData('name', value)} />
                                <TextInput label="Correo" type="email" value={profile.data.email} error={profile.errors.email} onChange={(value) => profile.setData('email', value)} />
                            </div>
                            <div className="grid gap-5 md:grid-cols-2">
                                <PhoneInput label="Telefono" countries={countries} country={profile.data.telefono_country_code} number={profile.data.telefono_national} setCountry={(value) => profile.setData('telefono_country_code', value)} setNumber={(value) => profile.setData('telefono_national', value)} />
                                <TextInput label="Direccion" value={profile.data.direccion} error={profile.errors.direccion} onChange={(value) => profile.setData('direccion', value)} required={false} />
                            </div>
                            {user.is_affiliate && (
                                <div className="mt-2 border-t border-white/10 pt-6">
                                    <h3 className="text-sm font-black uppercase tracking-widest text-white">Datos del socio Airbnb</h3>
                                    <div className="mt-5 grid gap-5 md:grid-cols-2">
                                        <TextInput label="Nombre comercial" value={profile.data.nombre_comercial} error={profile.errors.nombre_comercial} onChange={(value) => profile.setData('nombre_comercial', value)} />
                                        <TextInput label="NIT" value={profile.data.nit} error={profile.errors.nit} onChange={(value) => profile.setData('nit', value)} required={false} />
                                        <PhoneInput label="Telefono negocio" countries={countries} country={profile.data.telefono_negocio_country_code} number={profile.data.telefono_negocio_national} setCountry={(value) => profile.setData('telefono_negocio_country_code', value)} setNumber={(value) => profile.setData('telefono_negocio_national', value)} />
                                        <TextInput label="Direccion negocio" value={profile.data.direccion_negocio} onChange={(value) => profile.setData('direccion_negocio', value)} required={false} />
                                        <TextInput label="Metodo de pago" value={profile.data.metodo_pago} onChange={(value) => profile.setData('metodo_pago', value)} required={false} />
                                        <TextInput label="Titular" value={profile.data.titular_pago} onChange={(value) => profile.setData('titular_pago', value)} required={false} />
                                        <div className="md:col-span-2"><TextInput label="Cuenta o instrucciones de pago" value={profile.data.cuenta_pago} onChange={(value) => profile.setData('cuenta_pago', value)} required={false} /></div>
                                    </div>
                                </div>
                            )}
                            <button disabled={profile.processing} className="mt-2 inline-flex items-center justify-center gap-2 rounded-xl bg-[#fcca00] px-5 py-3 text-sm font-black text-black transition hover:bg-[#e8ba00] disabled:opacity-60"><i className="fas fa-check" /> Guardar perfil</button>
                        </form>
                    </section>
                    <section className="rounded-2xl border border-white/10 bg-[#0a0a0a] p-6">
                        <h2 className="text-lg font-black">Seguridad</h2>
                        <p className="mt-2 text-sm text-[#737373]">Usa una contrasena de minimo 8 caracteres con letras y numeros.</p>
                        <form onSubmit={(event) => { event.preventDefault(); password.put(urls.password, { preserveScroll: true, onSuccess: () => password.reset() }); }} className="mt-6 grid gap-4">
                            <PasswordInput placeholder="Contrasena actual" value={password.data.current_password} error={password.errors.current_password} onChange={(value) => password.setData('current_password', value)} />
                            <PasswordInput placeholder="Nueva contrasena" value={password.data.password} error={password.errors.password} onChange={(value) => password.setData('password', value)} />
                            <PasswordInput placeholder="Confirmar nueva contrasena" value={password.data.password_confirmation} onChange={(value) => password.setData('password_confirmation', value)} />
                            <button disabled={password.processing} className="rounded-xl border border-[#fcca00]/70 px-5 py-3 text-sm font-black text-[#fcca00] transition hover:bg-[#fcca00] hover:text-black disabled:opacity-60">Cambiar contrasena</button>
                        </form>
                    </section>
                </div>
            </div>
        </AdminLayout>
    );
}

function TextInput({ label, value, onChange, error, type = 'text', required = true }) {
    return <div><label className="text-[10px] font-black uppercase tracking-widest text-[#737373]">{label}</label><input type={type} value={value} onChange={(event) => onChange(event.target.value)} required={required} className="mt-2 w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]" />{error && <p className="mt-1 text-xs font-bold text-red-300">{error}</p>}</div>;
}

function PasswordInput({ placeholder, value, onChange, error }) {
    return <div><input type="password" value={value} onChange={(event) => onChange(event.target.value)} required placeholder={placeholder} className="w-full rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]" />{error && <p className="mt-1 text-xs font-bold text-red-300">{error}</p>}</div>;
}

function PhoneInput({ label, countries, country, number, setCountry, setNumber }) {
    return <div><label className="text-[10px] font-black uppercase tracking-widest text-[#737373]">{label}</label><div className="mt-2 grid grid-cols-[110px_1fr] gap-2"><select value={country} onChange={(event) => setCountry(event.target.value)} className="rounded-xl border border-white/10 bg-black px-3 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]">{Object.entries(countries).map(([code, data]) => <option key={code} value={code}>{code} {data.dial}</option>)}</select><input value={number} onChange={(event) => setNumber(event.target.value)} className="rounded-xl border border-white/10 bg-black px-4 py-3 text-sm font-semibold text-white outline-none focus:border-[#fcca00]" /></div></div>;
}

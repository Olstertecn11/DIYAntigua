import { Link, useForm } from '@inertiajs/react';
import { useMemo, useState } from 'react';

function FieldError({ message }) {
    if (!message) {
        return null;
    }

    return <p className="mt-2 text-sm font-bold text-red-600">{message}</p>;
}

function PasswordInput({ id, label, value, onChange, error, autoComplete }) {
    const [visible, setVisible] = useState(false);

    return (
        <div>
            <label htmlFor={id} className="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700">
                {label}
            </label>
            <div className="relative">
                <span className="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-700">
                    <i className="fas fa-lock" />
                </span>
                <input
                    id={id}
                    type={visible ? 'text' : 'password'}
                    value={value}
                    onChange={onChange}
                    required
                    autoComplete={autoComplete}
                    placeholder={label}
                    className={[
                        'w-full rounded-full border bg-white/90 py-3.5 pl-12 pr-12 text-sm font-bold text-slate-900 shadow-sm outline-none transition',
                        error ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-blue-100 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100',
                    ].join(' ')}
                />
                <button
                    type="button"
                    onClick={() => setVisible((value) => !value)}
                    className="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 transition hover:text-blue-800"
                    aria-label={visible ? 'Ocultar contrasena' : 'Mostrar contrasena'}
                >
                    <i className={`fas ${visible ? 'fa-eye-slash' : 'fa-eye'}`} />
                </button>
            </div>
            <FieldError message={error} />
        </div>
    );
}

export default function Register({ countries = [], urls = {} }) {
    const defaultCountry = useMemo(() => countries.find((country) => country.code === 'GT') || countries[0], [countries]);
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        telefono_country_code: defaultCountry?.code || '',
        telefono_national: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (event) => {
        event.preventDefault();
        post(urls.register || '/register');
    };

    return (
        <main className="min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(250,204,21,0.16),_transparent_22%),linear-gradient(135deg,#f8fafc_0%,#eef2ff_50%,#f8fafc_100%)] px-4 py-8 text-slate-950 sm:px-6 lg:py-14">
            <div className="mx-auto flex min-h-[82vh] max-w-7xl items-center justify-center">
                <section className="w-full max-w-6xl overflow-hidden rounded-[2rem] border border-white/60 bg-white/80 shadow-[0_30px_100px_rgba(15,23,42,0.18)] backdrop-blur-xl">
                    <div className="grid lg:grid-cols-2">
                        <div className="relative flex flex-col justify-between overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(250,204,21,0.20),_transparent_24%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.18),_transparent_26%),linear-gradient(135deg,#eff6ff_0%,#dbeafe_38%,#f8fafc_100%)] px-6 py-8 sm:px-10 lg:px-12">
                            <div>
                                <div className="mb-10 flex items-center justify-between gap-4">
                                    <Link href={urls.home || '/'} className="flex items-center gap-3 no-underline">
                                        <span className="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl bg-slate-950 shadow-lg">
                                            <img src="/images/logo.png" alt="DIY Antigua" className="h-9 w-9 object-contain" />
                                        </span>
                                        <span>
                                            <span className="block text-sm font-black leading-tight text-slate-950">DIY Antigua</span>
                                            <span className="block text-xs text-slate-600">Private Transfers</span>
                                        </span>
                                    </Link>
                                    <span className="hidden rounded-full border border-white/60 bg-white/70 px-3 py-1 text-[11px] font-black uppercase tracking-[0.16em] text-blue-900 shadow-sm sm:inline-flex">
                                        Crear cuenta
                                    </span>
                                </div>

                                <div className="mb-8">
                                    <h1 className="mb-2 text-3xl font-black tracking-tight sm:text-4xl">Crear cuenta</h1>
                                    <p className="mb-0 leading-relaxed text-slate-600">
                                        Registrate para guardar tus datos, consultar tus reservas y continuar tus traslados de forma mas rapida.
                                    </p>
                                </div>

                                <form onSubmit={submit} className="space-y-5">
                                    <div>
                                        <label htmlFor="name" className="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700">
                                            Nombre completo
                                        </label>
                                        <div className="relative">
                                            <span className="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-700">
                                                <i className="fas fa-user" />
                                            </span>
                                            <input
                                                id="name"
                                                type="text"
                                                value={data.name}
                                                onChange={(event) => setData('name', event.target.value)}
                                                required
                                                autoComplete="name"
                                                autoFocus
                                                placeholder="Ej. Juan Perez"
                                                className={[
                                                    'w-full rounded-full border bg-white/90 py-3.5 pl-12 pr-4 text-sm font-bold text-slate-900 shadow-sm outline-none transition',
                                                    errors.name ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-blue-100 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100',
                                                ].join(' ')}
                                            />
                                        </div>
                                        <FieldError message={errors.name} />
                                    </div>

                                    <div>
                                        <label htmlFor="email" className="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700">
                                            Correo electronico
                                        </label>
                                        <div className="relative">
                                            <span className="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-700">
                                                <i className="fas fa-envelope" />
                                            </span>
                                            <input
                                                id="email"
                                                type="email"
                                                value={data.email}
                                                onChange={(event) => setData('email', event.target.value)}
                                                required
                                                autoComplete="email"
                                                placeholder="usuario@gmail.com"
                                                className={[
                                                    'w-full rounded-full border bg-white/90 py-3.5 pl-12 pr-4 text-sm font-bold text-slate-900 shadow-sm outline-none transition',
                                                    errors.email ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-blue-100 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100',
                                                ].join(' ')}
                                            />
                                        </div>
                                        <FieldError message={errors.email} />
                                    </div>

                                    <div>
                                        <label htmlFor="telefono_country_code" className="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700">
                                            Telefono
                                        </label>
                                        <div className="grid gap-3 sm:grid-cols-[180px_1fr]">
                                            <select
                                                id="telefono_country_code"
                                                value={data.telefono_country_code}
                                                onChange={(event) => setData('telefono_country_code', event.target.value)}
                                                className="rounded-full border border-blue-100 bg-white/90 px-4 py-3.5 text-sm font-bold text-slate-900 shadow-sm outline-none transition focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                            >
                                                {countries.map((country) => (
                                                    <option key={country.code} value={country.code}>
                                                        {country.dial} {country.name}
                                                    </option>
                                                ))}
                                            </select>
                                            <input
                                                type="tel"
                                                value={data.telefono_national}
                                                onChange={(event) => setData('telefono_national', event.target.value)}
                                                autoComplete="tel-national"
                                                placeholder="Numero de telefono"
                                                className={[
                                                    'rounded-full border bg-white/90 px-4 py-3.5 text-sm font-bold text-slate-900 shadow-sm outline-none transition',
                                                    errors.telefono_national || errors.telefono_country_code ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-blue-100 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100',
                                                ].join(' ')}
                                            />
                                        </div>
                                        <FieldError message={errors.telefono_country_code || errors.telefono_national} />
                                    </div>

                                    <PasswordInput
                                        id="password"
                                        label="Contrasena"
                                        value={data.password}
                                        onChange={(event) => setData('password', event.target.value)}
                                        error={errors.password}
                                        autoComplete="new-password"
                                    />

                                    <PasswordInput
                                        id="password_confirmation"
                                        label="Confirmar contrasena"
                                        value={data.password_confirmation}
                                        onChange={(event) => setData('password_confirmation', event.target.value)}
                                        error={errors.password_confirmation}
                                        autoComplete="new-password"
                                    />

                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full rounded-full bg-gradient-to-r from-slate-950 via-slate-900 to-blue-950 px-6 py-3.5 text-sm font-black uppercase tracking-[0.14em] text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_40px_rgba(15,23,42,0.28)] disabled:cursor-not-allowed disabled:opacity-70"
                                    >
                                        {processing ? 'Creando...' : 'Crear cuenta'}
                                    </button>
                                </form>
                            </div>

                            <div className="pt-8">
                                <div className="flex flex-col items-center justify-between gap-4 border-t border-blue-100 pt-6 sm:flex-row">
                                    <p className="mb-0 text-sm text-slate-600">Ya tienes una cuenta?</p>
                                    <Link href={urls.login || '/login'} className="inline-flex items-center justify-center rounded-full bg-yellow-400 px-5 py-2.5 text-sm font-black text-slate-950 no-underline shadow-lg shadow-yellow-500/20 transition hover:bg-yellow-300">
                                        Iniciar sesion
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <aside className="relative hidden min-h-[760px] overflow-hidden bg-slate-950 text-white lg:flex">
                            <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(250,204,21,0.22),_transparent_28%),radial-gradient(circle_at_bottom_left,_rgba(59,130,246,0.18),_transparent_24%),linear-gradient(135deg,#020617_0%,#0f172a_38%,#020617_100%)]" />
                            <div className="absolute left-[18%] top-[8%] h-[86%] w-24 rotate-[22deg] rounded-full bg-gradient-to-b from-yellow-300/0 via-yellow-400/80 to-yellow-300/0 blur-xl" />
                            <div className="relative z-10 flex min-h-full w-full flex-col justify-between p-8 xl:p-10">
                                <div className="flex items-center justify-between">
                                    <div className="text-xs font-black uppercase tracking-[0.18em] text-[#facc15]">DIY Antigua</div>
                                    <div className="flex items-center gap-6 text-[11px] font-bold uppercase tracking-[0.16em] text-white/60">
                                        <span>Cuenta</span>
                                        <span>Reservas</span>
                                        <span>Seguridad</span>
                                    </div>
                                </div>
                                <div className="ml-auto max-w-md text-right">
                                    <p className="mb-5 inline-flex rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] font-black uppercase tracking-[0.16em] text-yellow-300">
                                        Nuevo acceso
                                    </p>
                                    <h2 className="mb-4 text-5xl font-black leading-none tracking-tight xl:text-6xl">
                                        Join us<span className="text-yellow-300">.</span>
                                    </h2>
                                    <p className="text-base leading-relaxed text-white/70">
                                        Crea tu cuenta y disfruta una experiencia mas rapida para gestionar tus traslados privados en Guatemala.
                                    </p>
                                </div>
                                <div className="ml-auto grid max-w-md grid-cols-3 gap-3">
                                    {['Reservas', 'Seguridad', 'Rapidez'].map((label, index) => (
                                        <div key={label} className="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                                            <div className={index === 0 ? 'mb-2 text-yellow-300' : index === 1 ? 'mb-2 text-blue-300' : 'mb-2 text-white'}>
                                                <i className={`fas ${index === 0 ? 'fa-route' : index === 1 ? 'fa-user-shield' : 'fa-bolt'}`} />
                                            </div>
                                            <p className="mb-1 text-sm font-black">{label}</p>
                                            <p className="mb-0 text-xs text-white/60">Cuenta protegida.</p>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </aside>
                    </div>
                </section>
            </div>
        </main>
    );
}

import { Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

function BrandMark({ home }) {
    return (
        <Link href={home || '/'} className="flex items-center gap-3 no-underline">
            <span className="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl bg-slate-950 shadow-lg">
                <img src="/images/logo.png" alt="DIY Antigua" className="h-9 w-9 object-contain" />
            </span>
            <span>
                <span className="block text-sm font-black leading-tight text-slate-950">DIY Antigua</span>
                <span className="block text-xs text-slate-600">Private Transfers</span>
            </span>
        </Link>
    );
}

function FieldError({ message }) {
    if (!message) {
        return null;
    }

    return <p className="mt-2 text-sm font-bold text-red-600">{message}</p>;
}

function AuthAside({ sideTitle, sideCopy, mode }) {
    const labels = mode === 'admin'
        ? ['Rutas', 'Reservas', 'Pagos']
        : mode === 'socio'
            ? ['Referidos', 'Comisiones', 'Perfil']
            : ['Reservas', 'Seguridad', 'Rapidez'];

    return (
        <aside className="relative hidden min-h-[680px] overflow-hidden bg-slate-950 text-white lg:flex">
            <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(250,204,21,0.22),_transparent_28%),radial-gradient(circle_at_bottom_left,_rgba(59,130,246,0.18),_transparent_24%),linear-gradient(135deg,#020617_0%,#0f172a_38%,#020617_100%)]" />
            <div className="absolute left-[18%] top-[8%] h-[86%] w-24 rotate-[22deg] rounded-full bg-gradient-to-b from-yellow-300/0 via-yellow-400/80 to-yellow-300/0 blur-xl" />
            <div className="absolute left-[32%] top-0 h-full w-20 rotate-[20deg] rounded-full bg-gradient-to-b from-white/0 via-white/25 to-white/0 blur-2xl" />
            <div className="relative z-10 flex min-h-full w-full flex-col justify-between p-8 xl:p-10">
                <div className="flex items-center justify-between">
                    <div className="text-xs font-black uppercase tracking-[0.18em] text-[#facc15]">DIY Antigua</div>
                    <div className="flex items-center gap-6 text-[11px] font-bold uppercase tracking-[0.16em] text-white/60">
                        {labels.map((label) => <span key={label}>{label}</span>)}
                    </div>
                </div>

                <div className="ml-auto max-w-md text-right">
                    <p className="mb-5 inline-flex rounded-full border border-white/10 bg-white/5 px-4 py-2 text-[11px] font-black uppercase tracking-[0.16em] text-yellow-300">
                        Premium Access
                    </p>
                    <h2 className="mb-4 text-5xl font-black leading-none tracking-tight xl:text-6xl">{sideTitle}</h2>
                    <p className="text-base leading-relaxed text-white/70">{sideCopy}</p>
                </div>

                <div className="ml-auto grid max-w-md grid-cols-3 gap-3">
                    {labels.map((label, index) => (
                        <div key={label} className="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-md">
                            <div className={index === 0 ? 'mb-2 text-yellow-300' : index === 1 ? 'mb-2 text-blue-300' : 'mb-2 text-white'}>
                                <i className={`fas ${index === 0 ? 'fa-route' : index === 1 ? 'fa-user-shield' : 'fa-bolt'}`} />
                            </div>
                            <p className="mb-1 text-sm font-black">{label}</p>
                            <p className="mb-0 text-xs text-white/60">Acceso protegido.</p>
                        </div>
                    ))}
                </div>
            </div>
        </aside>
    );
}

export default function Login({
    mode = 'client',
    title = 'Iniciar sesion',
    kicker = 'Acceso privado',
    copy = 'Accede a tu cuenta para gestionar tus reservas.',
    sideTitle = 'Welcome.',
    sideCopy = 'Accede a tu cuenta y continua con tu experiencia.',
    showRegister = true,
    urls = {},
}) {
    const [showPassword, setShowPassword] = useState(false);
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (event) => {
        event.preventDefault();
        post(urls.login || '/login');
    };

    return (
        <main className="min-h-screen overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(250,204,21,0.14),_transparent_20%),linear-gradient(135deg,#f8fafc_0%,#eef4ff_45%,#f8fafc_100%)] px-4 py-8 text-slate-950 sm:px-6 lg:py-14">
            <div className="mx-auto flex min-h-[82vh] max-w-7xl items-center justify-center">
                <section className="w-full max-w-6xl overflow-hidden rounded-[2rem] border border-white/60 bg-white/80 shadow-[0_30px_100px_rgba(15,23,42,0.18)] backdrop-blur-xl">
                    <div className="grid lg:grid-cols-2">
                        <div className="relative flex flex-col justify-between overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(250,204,21,0.18),_transparent_22%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.18),_transparent_25%),linear-gradient(135deg,#eff6ff_0%,#dbeafe_35%,#f8fafc_100%)] px-6 py-8 sm:px-10 lg:px-12">
                            <div className="relative z-10">
                                <div className="mb-10 flex items-center justify-between gap-4">
                                    <BrandMark home={urls.home} />
                                    <span className="hidden rounded-full border border-white/60 bg-white/70 px-3 py-1 text-[11px] font-black uppercase tracking-[0.16em] text-blue-900 shadow-sm sm:inline-flex">
                                        {kicker}
                                    </span>
                                </div>

                                <div className="mb-8">
                                    <h1 className="mb-2 text-3xl font-black tracking-tight sm:text-4xl">{title}</h1>
                                    <p className="mb-0 leading-relaxed text-slate-600">{copy}</p>
                                </div>

                                <form onSubmit={submit} className="space-y-5">
                                    <div>
                                        <label htmlFor="email" className="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700">
                                            Correo electronico
                                        </label>
                                        <div className="relative">
                                            <span className="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-700">
                                                <i className="fas fa-user" />
                                            </span>
                                            <input
                                                id="email"
                                                type="email"
                                                value={data.email}
                                                onChange={(event) => setData('email', event.target.value)}
                                                required
                                                autoComplete="email"
                                                autoFocus
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
                                        <label htmlFor="password" className="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700">
                                            Contrasena
                                        </label>
                                        <div className="relative">
                                            <span className="absolute inset-y-0 left-0 flex items-center pl-4 text-blue-700">
                                                <i className="fas fa-lock" />
                                            </span>
                                            <input
                                                id="password"
                                                type={showPassword ? 'text' : 'password'}
                                                value={data.password}
                                                onChange={(event) => setData('password', event.target.value)}
                                                required
                                                autoComplete="current-password"
                                                placeholder="Ingresa tu contrasena"
                                                className={[
                                                    'w-full rounded-full border bg-white/90 py-3.5 pl-12 pr-12 text-sm font-bold text-slate-900 shadow-sm outline-none transition',
                                                    errors.password ? 'border-red-400 focus:border-red-500 focus:ring-4 focus:ring-red-100' : 'border-blue-100 focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100',
                                                ].join(' ')}
                                            />
                                            <button
                                                type="button"
                                                onClick={() => setShowPassword((value) => !value)}
                                                className="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-500 transition hover:text-blue-800"
                                                aria-label={showPassword ? 'Ocultar contrasena' : 'Mostrar contrasena'}
                                            >
                                                <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'}`} />
                                            </button>
                                        </div>
                                        <FieldError message={errors.password} />
                                    </div>

                                    <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <label htmlFor="remember" className="inline-flex cursor-pointer items-center gap-3">
                                            <input
                                                id="remember"
                                                type="checkbox"
                                                checked={data.remember}
                                                onChange={(event) => setData('remember', event.target.checked)}
                                                className="h-4 w-4 rounded border-slate-300 text-yellow-500 focus:ring-yellow-400"
                                            />
                                            <span className="text-sm font-semibold text-slate-700">Recordarme</span>
                                        </label>

                                        {urls.passwordRequest && (
                                            <Link href={urls.passwordRequest} className="text-sm font-bold text-blue-700 no-underline hover:text-blue-900">
                                                Olvidaste tu contrasena?
                                            </Link>
                                        )}
                                    </div>

                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full rounded-full bg-gradient-to-r from-slate-950 via-slate-900 to-blue-950 px-6 py-3.5 text-sm font-black uppercase tracking-[0.14em] text-white shadow-[0_12px_30px_rgba(15,23,42,0.22)] transition hover:-translate-y-0.5 hover:shadow-[0_18px_40px_rgba(15,23,42,0.28)] disabled:cursor-not-allowed disabled:opacity-70"
                                    >
                                        {processing ? 'Entrando...' : 'Login'}
                                    </button>
                                </form>
                            </div>

                            {showRegister && urls.register && (
                                <div className="relative z-10 pt-8">
                                    <div className="flex flex-col items-center justify-between gap-4 border-t border-blue-100 pt-6 sm:flex-row">
                                        <p className="mb-0 text-sm text-slate-600">Aun no tienes una cuenta?</p>
                                        <Link href={urls.register} className="inline-flex items-center justify-center rounded-full bg-yellow-400 px-5 py-2.5 text-sm font-black text-slate-950 no-underline shadow-lg shadow-yellow-500/20 transition hover:bg-yellow-300">
                                            Crear cuenta
                                        </Link>
                                    </div>
                                </div>
                            )}
                        </div>

                        <AuthAside mode={mode} sideTitle={sideTitle} sideCopy={sideCopy} />
                    </div>
                </section>
            </div>
        </main>
    );
}

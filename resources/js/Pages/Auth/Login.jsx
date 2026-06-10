import { Link, useForm } from '@inertiajs/react';
import { useState } from 'react';

function BrandMark({ home }) {
    return (
        <Link href={home || '/'} className="flex items-center gap-3 no-underline">
            <span className="flex h-12 w-12 items-center justify-center overflow-hidden rounded-2xl border border-[#FCCA00]/40 bg-black shadow-[0_0_0_7px_rgba(252,202,0,.08)]">
                <img src="/images/logo.png" alt="DYANTIGUA" className="h-10 w-10 object-contain" />
            </span>
            <span>
                <span className="block text-lg font-black leading-tight text-white">DYANTIGUA</span>
                <span className="block text-[10px] font-black uppercase tracking-[0.28em] text-[#FCCA00]">Private Transfers</span>
            </span>
        </Link>
    );
}

function FieldError({ message }) {
    if (!message) return null;
    return <p className="mt-2 text-sm font-bold text-red-300">{message}</p>;
}

function AuthAside({ sideTitle, sideCopy, mode }) {
    const labels = mode === 'admin'
        ? [['Reservas', 'fa-calendar-check'], ['Rutas', 'fa-route'], ['Pagos', 'fa-wallet']]
        : mode === 'socio'
            ? [['Referidos', 'fa-link'], ['Comisiones', 'fa-coins'], ['Perfil', 'fa-user-gear']]
            : [['Traslados', 'fa-van-shuttle'], ['Pagos', 'fa-shield-halved'], ['Comprobantes', 'fa-receipt']];

    return (
        <aside className="relative hidden min-h-[690px] overflow-hidden bg-[#080806] text-white lg:flex">
            <div className="absolute inset-0 bg-[linear-gradient(135deg,rgba(252,202,0,.18)_0%,transparent_32%),linear-gradient(180deg,#11110f_0%,#050505_100%)]" />
            <div className="absolute right-[-90px] top-16 h-72 w-72 rounded-full border border-[#FCCA00]/20" />
            <div className="absolute bottom-[-120px] left-10 h-80 w-80 rounded-full border border-white/10" />
            <div className="absolute inset-x-10 top-28 h-px bg-gradient-to-r from-transparent via-[#FCCA00]/70 to-transparent" />
            <div className="relative z-10 flex min-h-full w-full flex-col justify-between p-10">
                <div className="flex items-center justify-between">
                    <span className="rounded-full border border-[#FCCA00]/30 bg-[#FCCA00]/10 px-4 py-2 text-[10px] font-black uppercase tracking-[0.22em] text-[#FCCA00]">
                        Secure Access
                    </span>
                    <span className="text-[10px] font-black uppercase tracking-[0.22em] text-white/35">DYANTIGUA</span>
                </div>

                <div className="max-w-lg">
                    <p className="mb-5 inline-flex rounded-full bg-white/[0.06] px-4 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-white/60">
                        {mode === 'admin' ? 'Panel operativo' : mode === 'socio' ? 'Panel de socio' : 'Cuenta de pasajero'}
                    </p>
                    <h2 className="mb-5 text-5xl font-black leading-none tracking-tight xl:text-6xl">{sideTitle}</h2>
                    <p className="max-w-md text-base leading-relaxed text-white/65">{sideCopy}</p>
                </div>

                <div className="grid grid-cols-3 gap-3">
                    {labels.map(([label, icon], index) => (
                        <div key={label} className="rounded-3xl border border-white/10 bg-white/[0.045] p-4 backdrop-blur">
                            <span className={['mb-4 flex h-10 w-10 items-center justify-center rounded-2xl', index === 0 ? 'bg-[#FCCA00] text-black' : 'bg-white/10 text-[#FCCA00]'].join(' ')}>
                                <i className={`fas ${icon}`} />
                            </span>
                            <p className="mb-1 text-sm font-black">{label}</p>
                            <p className="mb-0 text-xs leading-relaxed text-white/45">Control claro y seguro.</p>
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
        <main className="min-h-screen overflow-hidden bg-[linear-gradient(90deg,#FCCA00_0_10px,transparent_10px),linear-gradient(135deg,#11110f_0%,#050505_45%,#f7f7f2_45%,#ffffff_100%)] px-4 py-8 text-white sm:px-6 lg:py-14">
            <div className="mx-auto flex min-h-[82vh] max-w-7xl items-center justify-center">
                <section className="w-full max-w-6xl overflow-hidden rounded-[2.25rem] border border-white/10 bg-[#090908] shadow-[0_34px_110px_rgba(0,0,0,.42)]">
                    <div className="grid lg:grid-cols-[0.92fr_1.08fr]">
                        <div className="relative flex flex-col justify-between overflow-hidden bg-[#0d0d0b] px-6 py-8 sm:px-10 lg:px-12">
                            <div className="absolute inset-0 bg-[radial-gradient(circle_at_20%_12%,rgba(252,202,0,.18),transparent_25%),linear-gradient(180deg,rgba(255,255,255,.04),transparent)]" />
                            <div className="relative z-10">
                                <div className="mb-10 flex items-center justify-between gap-4">
                                    <BrandMark home={urls.home} />
                                    <span className="hidden rounded-full border border-[#FCCA00]/25 bg-[#FCCA00]/10 px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.18em] text-[#FCCA00] sm:inline-flex">
                                        {kicker}
                                    </span>
                                </div>

                                <div className="mb-8">
                                    <h1 className="mb-3 text-4xl font-black tracking-tight text-white sm:text-5xl">{title}</h1>
                                    <p className="mb-0 leading-relaxed text-white/58">{copy}</p>
                                </div>

                                <form onSubmit={submit} className="space-y-5">
                                    <Field
                                        id="email"
                                        icon="fa-envelope"
                                        label="Correo electronico"
                                        type="email"
                                        value={data.email}
                                        onChange={(value) => setData('email', value)}
                                        error={errors.email}
                                        autoComplete="email"
                                        placeholder="usuario@gmail.com"
                                        autoFocus
                                    />

                                    <Field
                                        id="password"
                                        icon="fa-lock"
                                        label="Contrasena"
                                        type={showPassword ? 'text' : 'password'}
                                        value={data.password}
                                        onChange={(value) => setData('password', value)}
                                        error={errors.password}
                                        autoComplete="current-password"
                                        placeholder="Ingresa tu contrasena"
                                        trailing={(
                                            <button
                                                type="button"
                                                onClick={() => setShowPassword((value) => !value)}
                                                className="text-white/45 transition hover:text-[#FCCA00]"
                                                aria-label={showPassword ? 'Ocultar contrasena' : 'Mostrar contrasena'}
                                            >
                                                <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'}`} />
                                            </button>
                                        )}
                                    />

                                    <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <label htmlFor="remember" className="inline-flex cursor-pointer items-center gap-3">
                                            <input
                                                id="remember"
                                                type="checkbox"
                                                checked={data.remember}
                                                onChange={(event) => setData('remember', event.target.checked)}
                                                className="h-4 w-4 rounded border-white/20 bg-black text-[#FCCA00] focus:ring-[#FCCA00]"
                                            />
                                            <span className="text-sm font-semibold text-white/68">Recordarme</span>
                                        </label>

                                        {urls.passwordRequest && (
                                            <Link href={urls.passwordRequest} className="text-sm font-bold text-[#FCCA00] no-underline hover:text-yellow-200">
                                                Olvidaste tu contrasena?
                                            </Link>
                                        )}
                                    </div>

                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="flex w-full items-center justify-center gap-3 rounded-full bg-[#FCCA00] px-6 py-4 text-sm font-black uppercase tracking-[0.16em] text-black shadow-[0_16px_45px_rgba(252,202,0,.30)] transition hover:-translate-y-0.5 hover:bg-yellow-300 disabled:cursor-not-allowed disabled:opacity-70"
                                    >
                                        {processing && <i className="fas fa-circle-notch animate-spin" />}
                                        {processing ? 'Entrando...' : 'Entrar'}
                                    </button>
                                </form>
                            </div>

                            {showRegister && urls.register && (
                                <div className="relative z-10 pt-8">
                                    <div className="flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 sm:flex-row">
                                        <p className="mb-0 text-sm text-white/55">Aun no tienes una cuenta?</p>
                                        <Link href={urls.register} className="inline-flex items-center justify-center rounded-full border border-white/12 px-5 py-2.5 text-sm font-black text-white no-underline transition hover:border-[#FCCA00]/45 hover:text-[#FCCA00]">
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

function Field({ id, icon, label, value, onChange, error, trailing = null, ...props }) {
    return (
        <div>
            <label htmlFor={id} className="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-white/58">
                {label}
            </label>
            <div className={[
                'flex items-center gap-3 rounded-2xl border bg-white/[0.06] px-4 py-3.5 shadow-sm transition focus-within:ring-4',
                error ? 'border-red-400 focus-within:ring-red-500/10' : 'border-white/10 focus-within:border-[#FCCA00] focus-within:ring-[#FCCA00]/10',
            ].join(' ')}
            >
                <i className={`fas ${icon} text-[#FCCA00]`} />
                <input
                    id={id}
                    value={value}
                    onChange={(event) => onChange(event.target.value)}
                    required
                    className="min-w-0 flex-1 border-0 bg-transparent text-sm font-bold text-white outline-none placeholder:text-white/28"
                    {...props}
                />
                {trailing}
            </div>
            <FieldError message={error} />
        </div>
    );
}

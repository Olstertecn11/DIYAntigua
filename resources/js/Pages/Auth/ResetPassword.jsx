import { Head, Link, useForm } from '@inertiajs/react';

export default function ResetPassword({ token, email = '', urls = {} }) {
    const { data, setData, post, processing, errors } = useForm({
        token,
        email: email || '',
        password: '',
        password_confirmation: '',
    });

    const submit = (event) => {
        event.preventDefault();
        post(urls.reset || '/password/reset');
    };

    return (
        <main className="flex min-h-screen items-center justify-center bg-[linear-gradient(135deg,#f8fafc_0%,#eef4ff_50%,#f8fafc_100%)] px-4 py-10 text-slate-950">
            <Head title="Nueva contrasena" />
            <section className="w-full max-w-md rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-[0_24px_70px_rgba(15,23,42,0.14)] backdrop-blur md:p-8">
                <Link href={urls.home || '/'} className="mb-8 flex items-center gap-3 no-underline">
                    <span className="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl bg-slate-950">
                        <img src="/images/logo.png" alt="DYANTIGUA" className="h-9 w-9 object-contain" />
                    </span>
                    <span>
                        <span className="block text-sm font-black text-slate-950">DYANTIGUA</span>
                        <span className="block text-xs text-slate-600">Private Transfers</span>
                    </span>
                </Link>
                <h1 className="mb-2 text-3xl font-black">Nueva contrasena</h1>
                <p className="mb-8 text-sm leading-relaxed text-slate-600">Crea una contrasena nueva para volver a entrar a tu cuenta.</p>
                <form onSubmit={submit} className="space-y-5">
                    <Field label="Correo" type="email" value={data.email} onChange={(value) => setData('email', value)} error={errors.email} autoComplete="email" />
                    <Field label="Contrasena" type="password" value={data.password} onChange={(value) => setData('password', value)} error={errors.password} autoComplete="new-password" />
                    <Field label="Confirmar contrasena" type="password" value={data.password_confirmation} onChange={(value) => setData('password_confirmation', value)} error={errors.password_confirmation} autoComplete="new-password" />
                    <button disabled={processing} className="w-full rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black uppercase tracking-widest text-white transition hover:bg-slate-800 disabled:opacity-60">
                        {processing ? 'Actualizando...' : 'Actualizar contrasena'}
                    </button>
                </form>
                <Link href={urls.login || '/login'} className="mt-5 block text-center text-sm font-black text-blue-700 no-underline">
                    Volver al login
                </Link>
            </section>
        </main>
    );
}

function Field({ label, value, onChange, error, type = 'text', autoComplete }) {
    return (
        <div>
            <label className="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700">{label}</label>
            <input
                type={type}
                value={value}
                onChange={(event) => onChange(event.target.value)}
                required
                autoComplete={autoComplete}
                className={[
                    'w-full rounded-2xl border bg-white px-4 py-3 text-sm font-bold text-slate-900 outline-none transition focus:ring-4',
                    error ? 'border-red-400 focus:border-red-500 focus:ring-red-100' : 'border-slate-200 focus:border-yellow-400 focus:ring-yellow-100',
                ].join(' ')}
            />
            {error && <p className="mt-2 text-sm font-bold text-red-600">{error}</p>}
        </div>
    );
}

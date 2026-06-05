import { Head, Link, useForm } from '@inertiajs/react';

export default function ConfirmPassword({ urls = {} }) {
    const { data, setData, post, processing, errors } = useForm({
        password: '',
    });

    const submit = (event) => {
        event.preventDefault();
        post(urls.confirm || '/password/confirm');
    };

    return (
        <main className="flex min-h-screen items-center justify-center bg-[linear-gradient(135deg,#f8fafc_0%,#eef4ff_50%,#f8fafc_100%)] px-4 py-10 text-slate-950">
            <Head title="Confirmar contrasena" />
            <section className="w-full max-w-md rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-[0_24px_70px_rgba(15,23,42,0.14)] backdrop-blur md:p-8">
                <Link href={urls.home || '/'} className="mb-8 flex items-center gap-3 no-underline">
                    <span className="flex h-11 w-11 items-center justify-center overflow-hidden rounded-2xl bg-slate-950">
                        <img src="/images/logo.png" alt="DIY Antigua" className="h-9 w-9 object-contain" />
                    </span>
                    <span>
                        <span className="block text-sm font-black text-slate-950">DIY Antigua</span>
                        <span className="block text-xs text-slate-600">Private Transfers</span>
                    </span>
                </Link>
                <h1 className="mb-2 text-3xl font-black">Confirma tu contrasena</h1>
                <p className="mb-8 text-sm leading-relaxed text-slate-600">Por seguridad necesitamos confirmar tu contrasena antes de continuar.</p>
                <form onSubmit={submit} className="space-y-5">
                    <div>
                        <label className="mb-2 block text-[11px] font-black uppercase tracking-[0.16em] text-slate-700">Contrasena</label>
                        <input
                            type="password"
                            value={data.password}
                            onChange={(event) => setData('password', event.target.value)}
                            required
                            autoComplete="current-password"
                            className={[
                                'w-full rounded-2xl border bg-white px-4 py-3 text-sm font-bold text-slate-900 outline-none transition focus:ring-4',
                                errors.password ? 'border-red-400 focus:border-red-500 focus:ring-red-100' : 'border-slate-200 focus:border-yellow-400 focus:ring-yellow-100',
                            ].join(' ')}
                        />
                        {errors.password && <p className="mt-2 text-sm font-bold text-red-600">{errors.password}</p>}
                    </div>
                    <button disabled={processing} className="w-full rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black uppercase tracking-widest text-white transition hover:bg-slate-800 disabled:opacity-60">
                        {processing ? 'Confirmando...' : 'Confirmar'}
                    </button>
                </form>
                <Link href={urls.forgot || '/password/reset'} className="mt-5 block text-center text-sm font-black text-blue-700 no-underline">
                    Olvide mi contrasena
                </Link>
            </section>
        </main>
    );
}

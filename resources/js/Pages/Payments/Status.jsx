import { Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

const variants = {
    success: ['fa-circle-check', 'bg-green-500', 'text-green-300'],
    processing: ['fa-clock', 'bg-yellow-400', 'text-yellow-300'],
    declined: ['fa-circle-xmark', 'bg-red-500', 'text-red-300'],
    error: ['fa-triangle-exclamation', 'bg-red-500', 'text-red-300'],
};

export default function Status({ variant = 'processing', title, message, urls = {} }) {
    const [icon, bg, text] = variants[variant] || variants.processing;

    return (
        <PublicLayout>
        <main className="flex min-h-screen items-center justify-center bg-black px-4 pb-16 pt-36 text-white">
            <section className="w-full max-w-xl rounded-[2rem] border border-white/10 bg-[#111] p-8 text-center shadow-2xl">
                <div className={`mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl ${bg} text-2xl text-black`}>
                    <i className={`fas ${icon}`} />
                </div>
                <p className={`mb-3 text-xs font-black uppercase tracking-[0.2em] ${text}`}>Estado de pago</p>
                <h1 className="mb-4 text-4xl font-black">{title}</h1>
                <p className="mb-8 text-white/60">{message}</p>
                <div className="flex flex-col gap-3 sm:flex-row sm:justify-center">
                    {urls.primary && <Link href={urls.primary} className="rounded-2xl bg-yellow-400 px-5 py-3 text-sm font-black uppercase tracking-widest text-black no-underline">Continuar</Link>}
                    {urls.secondary && <a href={urls.secondary} className="rounded-2xl border border-white/10 px-5 py-3 text-sm font-black uppercase tracking-widest text-white no-underline">Ver detalle</a>}
                </div>
            </section>
        </main>
        </PublicLayout>
    );
}

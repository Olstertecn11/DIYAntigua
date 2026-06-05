import { Link, router, usePage } from '@inertiajs/react';

export default function PublicLayout({ children }) {
    const { props } = usePage();
    const routes = props.routes?.public || {};
    const user = props.auth?.user;

    return (
        <div className="min-h-screen bg-[#f7f6f1] text-[#363636]">
            <nav className="sticky top-0 z-50 border-b border-white/10 bg-black/95 px-4 py-4 text-white backdrop-blur">
                <div className="mx-auto flex max-w-7xl items-center justify-between">
                    <Link href={routes.home || '/'} className="flex items-center gap-3 no-underline">
                        <img src="/images/logo.png" alt="Logo" className="h-10 w-10 object-contain" />
                        <span className="hidden text-lg font-black uppercase tracking-tight text-white sm:block">DIY Antigua</span>
                    </Link>
                    <div className="flex items-center gap-3 text-xs font-black uppercase tracking-widest">
                        <Link href={`${routes.home || '/'}#booking`} className="text-white/80 no-underline hover:text-[#FCCA00]">Reservar</Link>
                        {user ? (
                            <>
                                <Link href={routes.reservasMine || '#'} className="text-white/80 no-underline hover:text-[#FCCA00]">Mis Reservas</Link>
                                <Link href={routes.profile || '#'} className="text-[#FCCA00] no-underline">Perfil</Link>
                                <button type="button" onClick={() => router.post(props.routes?.logout)} className="rounded-full border border-red-500/30 px-3 py-2 text-red-300 hover:bg-red-500/10">Salir</button>
                            </>
                        ) : (
                            <>
                                <Link href={routes.login || '#'} className="text-white/80 no-underline hover:text-[#FCCA00]">Login</Link>
                                <Link href={routes.register || '#'} className="rounded-full bg-[#FCCA00] px-4 py-2 text-black no-underline">Registro</Link>
                            </>
                        )}
                    </div>
                </div>
            </nav>
            {children}
            <footer className="bg-black px-4 py-8 text-center text-xs font-bold uppercase tracking-widest text-white/50">
                © 2026 DYANTIGUA
            </footer>
        </div>
    );
}

import { Link, router, usePage } from '@inertiajs/react';

function navHref(home, section) {
    return `${home || '/'}${section}`;
}

export default function PublicNavbar() {
    const { props } = usePage();
    const routes = props.routes?.public || {};
    const user = props.auth?.user;
    const home = routes.home || '/';

    return (
        <header className="fixed inset-x-0 top-0 z-50 border-b border-[#FCCA00]/20 bg-black/95 text-white backdrop-blur-xl">
            <div className="mx-auto flex h-[94px] max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <Link href={home} className="flex min-w-0 items-center gap-3 no-underline">
                    <span className="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-white/50 bg-black">
                        <img src="/images/logo.png" alt="DIY Antigua" className="h-10 w-10 object-contain" />
                    </span>
                    <span className="min-w-0 leading-none">
                        <span className="block text-xl font-black uppercase tracking-normal text-white sm:text-2xl">DIYANTIGUA</span>
                        <span className="mt-1 block text-[11px] font-black uppercase tracking-[0.22em] text-[#FCCA00] sm:text-xs">Private Transfers</span>
                    </span>
                </Link>

                <nav className="hidden items-center gap-7 text-sm font-black uppercase tracking-wide lg:flex">
                    <Link href={navHref(home, '#booking')} className="inline-flex items-center gap-2 text-white/80 no-underline transition hover:text-[#FCCA00]">
                        <i className="fas fa-route text-[#FCCA00]" />
                        Reservar traslado
                    </Link>
                    <Link href={navHref(home, '#destinos')} className="inline-flex items-center gap-2 text-white/80 no-underline transition hover:text-[#FCCA00]">
                        <i className="fas fa-map-location-dot text-[#FCCA00]" />
                        Destinos
                    </Link>
                    <Link href={navHref(home, '#nosotros')} className="inline-flex items-center gap-2 text-white/80 no-underline transition hover:text-[#FCCA00]">
                        <i className="fas fa-circle-info text-[#FCCA00]" />
                        Nosotros
                    </Link>
                    <Link href={navHref(home, '#tours')} className="inline-flex items-center gap-2 text-white/80 no-underline transition hover:text-[#FCCA00]">
                        <i className="fas fa-suitcase-rolling text-[#FCCA00]" />
                        Tours
                    </Link>
                </nav>

                <div className="flex shrink-0 items-center gap-2 sm:gap-3">
                    {user ? (
                        <>
                            <Link href={routes.reservasMine || '/mis-reservas'} className="hidden rounded-full border border-[#FCCA00]/60 px-5 py-3 text-sm font-black text-[#FCCA00] no-underline transition hover:bg-[#FCCA00]/10 md:inline-flex">
                                Mis reservas
                            </Link>
                            <Link href={routes.profile || '/perfil'} className="rounded-full bg-[#FCCA00] px-5 py-3 text-sm font-black text-black no-underline transition hover:bg-yellow-300">
                                Perfil
                            </Link>
                            <button
                                type="button"
                                onClick={() => router.post(props.routes?.logout)}
                                className="hidden rounded-full border border-white/15 px-4 py-3 text-sm font-black text-white/70 transition hover:text-white md:inline-flex"
                            >
                                Salir
                            </button>
                        </>
                    ) : (
                        <>
                            <Link href={routes.login || '/login'} className="hidden rounded-full border border-[#FCCA00]/60 px-6 py-3 text-sm font-black text-[#FCCA00] no-underline transition hover:bg-[#FCCA00]/10 sm:inline-flex lg:px-10">
                                Iniciar Sesion
                            </Link>
                            <Link href={routes.register || '/register'} className="rounded-full bg-[#FCCA00] px-5 py-3 text-sm font-black text-black no-underline transition hover:bg-yellow-300 sm:px-8 lg:px-10">
                                Registrarse
                            </Link>
                        </>
                    )}
                </div>
            </div>
        </header>
    );
}

import { Link, usePage } from '@inertiajs/react';

export default function PublicFooter() {
    const { props } = usePage();
    const routes = props.routes?.public || {};
    const home = routes.home || '/';

    return (
        <footer className="bg-black text-white">
            <div className="mx-auto grid max-w-7xl gap-10 px-6 py-14 md:grid-cols-[1.2fr_0.8fr_0.8fr_0.9fr] lg:px-8">
                <section>
                    <Link href={home} className="mb-5 inline-flex items-center gap-3 no-underline">
                        <span className="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/40">
                            <img src="/images/logo.png" alt="DIY Antigua" className="h-10 w-10 object-contain" />
                        </span>
                        <span>
                            <span className="block text-2xl font-black uppercase leading-none text-white">DIYANTIGUA</span>
                            <span className="mt-1 block text-xs font-black uppercase tracking-[0.22em] text-[#FCCA00]">Private Transfers</span>
                        </span>
                    </Link>
                    <p className="max-w-sm text-sm leading-relaxed text-white/55">
                        Traslados privados en Guatemala para aeropuerto, Antigua, Lago de Atitlan, Quetzaltenango y rutas turísticas.
                    </p>
                </section>

                <section>
                    <h3 className="mb-4 text-sm font-black uppercase tracking-[0.18em] text-[#FCCA00]">Navegacion</h3>
                    <div className="space-y-3 text-sm font-bold text-white/65">
                        <Link href={`${home}#booking`} className="block no-underline hover:text-[#FCCA00]">Reservar traslado</Link>
                        <Link href={`${home}#destinos`} className="block no-underline hover:text-[#FCCA00]">Destinos</Link>
                        <Link href={`${home}#nosotros`} className="block no-underline hover:text-[#FCCA00]">Nosotros</Link>
                        <Link href={`${home}#tours`} className="block no-underline hover:text-[#FCCA00]">Tours</Link>
                    </div>
                </section>

                <section>
                    <h3 className="mb-4 text-sm font-black uppercase tracking-[0.18em] text-[#FCCA00]">Cuenta</h3>
                    <div className="space-y-3 text-sm font-bold text-white/65">
                        <Link href={routes.login || '/login'} className="block no-underline hover:text-[#FCCA00]">Iniciar sesion</Link>
                        <Link href={routes.register || '/register'} className="block no-underline hover:text-[#FCCA00]">Registrarse</Link>
                        <Link href={routes.reservasMine || '/mis-reservas'} className="block no-underline hover:text-[#FCCA00]">Mis reservas</Link>
                    </div>
                </section>

                <section>
                    <h3 className="mb-4 text-sm font-black uppercase tracking-[0.18em] text-[#FCCA00]">Contacto</h3>
                    <div className="space-y-3 text-sm font-bold text-white/65">
                        <a href="https://wa.me/50200000000" className="block no-underline hover:text-[#FCCA00]">WhatsApp</a>
                        <span className="block">Guatemala</span>
                        <span className="block">Atencion para traslados privados</span>
                    </div>
                </section>
            </div>
            <div className="border-t border-white/10 px-6 py-5 text-center text-xs font-black uppercase tracking-[0.18em] text-white/35">
                © 2026 DIY Antigua Private Transfers
            </div>
        </footer>
    );
}

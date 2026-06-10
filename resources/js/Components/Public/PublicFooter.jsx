import { Link, usePage } from '@inertiajs/react';
import { useLanguage } from '@/Contexts/LanguageContext';

export default function PublicFooter() {
    const { props } = usePage();
    const routes = props.routes?.public || {};
    const home = routes.home || '/';
    const { t } = useLanguage();

    return (
        <footer className="bg-black text-white">
            <div className="mx-auto grid max-w-7xl gap-10 px-6 py-14 md:grid-cols-[1.2fr_0.8fr_0.8fr_0.9fr] lg:px-8">
                <section>
                    <Link href={home} className="mb-5 inline-flex items-center gap-3 no-underline">
                        <span className="flex h-12 w-12 items-center justify-center overflow-hidden rounded-2xl border border-[#FCCA00]/45 bg-white/5 shadow-[0_0_0_6px_rgba(252,202,0,.06)]">
                            <img src="/images/logo.png" alt="DYANTIGUA" className="h-10 w-10 object-contain" />
                        </span>
                        <span>
                            <span className="block text-2xl font-black uppercase leading-none text-white">DYANTIGUA</span>
                            <span className="mt-1 block text-xs font-black uppercase tracking-[0.22em] text-[#FCCA00]">Private Transfers</span>
                        </span>
                    </Link>
                    <p className="max-w-sm text-sm leading-relaxed text-white/55">
                        {t('Traslados privados en Guatemala para aeropuerto, Antigua, Lago de Atitlan, Quetzaltenango y rutas turísticas.')}
                    </p>
                </section>

                <section>
                    <h3 className="mb-4 text-sm font-black uppercase tracking-[0.18em] text-[#FCCA00]">{t('Navegacion')}</h3>
                    <div className="space-y-3 text-sm font-bold text-white/65">
                        <Link href={`${home}#booking`} className="block no-underline hover:text-[#FCCA00]">{t('Reservar traslado')}</Link>
                        <Link href={`${home}#destinos`} className="block no-underline hover:text-[#FCCA00]">{t('Destinos')}</Link>
                        <Link href={`${home}#nosotros`} className="block no-underline hover:text-[#FCCA00]">{t('Nosotros')}</Link>
                        <Link href={`${home}#destinos`} className="block no-underline hover:text-[#FCCA00]">{t('Tours')}</Link>
                    </div>
                </section>

                <section>
                    <h3 className="mb-4 text-sm font-black uppercase tracking-[0.18em] text-[#FCCA00]">{t('Cuenta')}</h3>
                    <div className="space-y-3 text-sm font-bold text-white/65">
                        <Link href={routes.login || '/login'} className="block no-underline hover:text-[#FCCA00]">{t('Iniciar sesion')}</Link>
                        <Link href={routes.register || '/register'} className="block no-underline hover:text-[#FCCA00]">{t('Registrarse')}</Link>
                        <Link href={routes.reservasMine || '/mis-reservas'} className="block no-underline hover:text-[#FCCA00]">{t('Mis reservas')}</Link>
                    </div>
                </section>

                <section>
                    <h3 className="mb-4 text-sm font-black uppercase tracking-[0.18em] text-[#FCCA00]">{t('Contacto')}</h3>
                    <div className="space-y-3 text-sm font-bold text-white/65">
                        <a href="https://wa.me/50200000000" className="block no-underline hover:text-[#FCCA00]">WhatsApp</a>
                        <span className="block">Guatemala</span>
                        <span className="block">{t('Atencion para traslados privados')}</span>
                    </div>
                </section>
            </div>
            <div className="border-t border-white/10 px-6 py-5 text-center text-xs font-black uppercase tracking-[0.18em] text-white/35">
                © 2026 DYANTIGUA Private Transfers
            </div>
        </footer>
    );
}

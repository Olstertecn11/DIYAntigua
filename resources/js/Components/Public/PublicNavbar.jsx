import { Link, router, usePage } from '@inertiajs/react';
import { useLanguage } from '@/Contexts/LanguageContext';
import { useEffect, useRef, useState } from 'react';

function navHref(home, section) {
    return `${home || '/'}${section}`;
}

function initials(name) {
    return String(name || 'U').trim().slice(0, 1).toUpperCase();
}

export default function PublicNavbar() {
    const { props } = usePage();
    const routes = props.routes?.public || {};
    const user = props.auth?.user;
    const home = routes.home || '/';
    const { t } = useLanguage();
    const [open, setOpen] = useState(false);
    const menuRef = useRef(null);

    useEffect(() => {
        if (!open) {
            return undefined;
        }

        const close = (event) => {
            if (menuRef.current && !menuRef.current.contains(event.target)) {
                setOpen(false);
            }
        };

        document.addEventListener('click', close);
        return () => document.removeEventListener('click', close);
    }, [open]);

    const logout = () => {
        setOpen(false);
        router.post(props.routes?.logout);
    };

    return (
        <header className="fixed inset-x-0 top-0 z-50 border-b border-[#FCCA00]/20 bg-[#080806]/95 text-white shadow-[0_18px_55px_rgba(0,0,0,.28)] backdrop-blur-xl">
            <div className="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <Link href={home} className="group flex min-w-0 items-center gap-3 no-underline">
                    <span className="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-[#FCCA00]/40 bg-black shadow-[0_0_0_5px_rgba(252,202,0,.06)] transition group-hover:border-[#FCCA00]">
                        <img src="/images/logo.png" alt="DYANTIGUA" className="h-9 w-9 object-contain" />
                    </span>
                    <span className="min-w-0 leading-none">
                        <span className="block text-lg font-black uppercase tracking-normal text-white sm:text-xl">DYANTIGUA</span>
                        <span className="mt-1 block text-[9px] font-black uppercase tracking-[0.26em] text-[#FCCA00] sm:text-[10px]">Private Transfers</span>
                    </span>
                </Link>

                <nav className="hidden items-center rounded-full border border-white/10 bg-white/[0.03] px-1.5 py-1.5 text-[11px] font-black uppercase tracking-wide lg:flex">
                    {[
                        ['fa-route', t('Reservar'), navHref(home, '#booking')],
                        ['fa-map-location-dot', t('Destinos'), navHref(home, '#destinos')],
                        ['fa-circle-info', t('Nosotros'), navHref(home, '#nosotros')],
                        ['fa-suitcase-rolling', t('Tours'), navHref(home, '#destinos')],
                    ].map(([icon, label, href]) => (
                        <Link key={label} href={href} className="inline-flex items-center gap-2 rounded-full px-3.5 py-2.5 text-white/72 no-underline transition hover:bg-white/5 hover:text-[#FCCA00]">
                            <i className={`fas ${icon} text-[#FCCA00]`} />
                            {label}
                        </Link>
                    ))}
                </nav>

                <div className="flex shrink-0 items-center gap-2 sm:gap-3">
                    {user ? (
                        <div ref={menuRef} className="relative">
                            <button
                                type="button"
                                onClick={(event) => {
                                    event.stopPropagation();
                                    setOpen((value) => !value);
                                }}
                                className="flex items-center gap-2.5 rounded-full border border-white/10 bg-white/[0.06] py-1.5 pl-1.5 pr-3 text-left transition hover:border-[#FCCA00]/50 hover:bg-white/[0.09]"
                                aria-expanded={open}
                            >
                                {user.avatar_base64 ? (
                                    <img src={user.avatar_base64} alt={user.name || 'Usuario'} className="h-9 w-9 rounded-full object-cover shadow-[0_10px_28px_rgba(252,202,0,.25)]" />
                                ) : (
                                    <span className="flex h-9 w-9 items-center justify-center rounded-full bg-[#FCCA00] text-sm font-black text-black shadow-[0_10px_28px_rgba(252,202,0,.25)]">
                                        {initials(user.name)}
                                    </span>
                                )}
                                <span className="hidden min-w-0 sm:block">
                                    <span className="block max-w-32 truncate text-sm font-black leading-tight text-white">{user.name}</span>
                                    <span className="block max-w-32 truncate text-[10px] font-bold uppercase tracking-widest text-white/45">{t('Mi cuenta')}</span>
                                </span>
                                <i className={`fas fa-chevron-down text-[10px] text-[#FCCA00] transition ${open ? 'rotate-180' : ''}`} />
                            </button>

                            {open && (
                                <div onClick={(event) => event.stopPropagation()} className="absolute right-0 mt-3 w-72 overflow-hidden rounded-3xl border border-white/10 bg-[#090908] p-2 text-white shadow-[0_28px_90px_rgba(0,0,0,.45)]">
                                    <div className="rounded-2xl bg-white/[0.05] p-4">
                                        <div className="flex items-center gap-3">
                                            {user.avatar_base64 ? (
                                                <img src={user.avatar_base64} alt={user.name || 'Usuario'} className="h-12 w-12 rounded-2xl object-cover" />
                                            ) : (
                                                <span className="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FCCA00] font-black text-black">{initials(user.name)}</span>
                                            )}
                                            <div className="min-w-0">
                                                <p className="mb-0 truncate text-sm font-black">{user.name}</p>
                                                <p className="mb-0 truncate text-xs font-semibold text-white/45">{user.email}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="mt-2 grid gap-1">
                                        <MenuLink href={routes.reservasMine || '/mis-reservas'} icon="fa-calendar-check" label={t('Mis reservas')} onClick={() => setOpen(false)} />
                                        <MenuLink href={routes.profile || '/perfil'} icon="fa-user-gear" label={t('Perfil')} onClick={() => setOpen(false)} />
                                        <MenuLink href={routes.security || '/perfil/seguridad'} icon="fa-shield-halved" label={t('Cambiar contraseña')} onClick={() => setOpen(false)} />
                                        <MenuLink href={navHref(home, '#booking')} icon="fa-plus" label={t('Nueva reserva')} onClick={() => setOpen(false)} />
                                        <button
                                            type="button"
                                            onClick={logout}
                                            className="mt-1 flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-black text-red-200 transition hover:bg-red-500/10 hover:text-red-100"
                                        >
                                            <i className="fas fa-power-off w-5 text-center" />
                                            {t('Salir')}
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    ) : (
                        <>
                            <Link href={routes.login || '/login'} className="hidden rounded-full border border-[#FCCA00]/45 px-5 py-3 text-xs font-black uppercase tracking-wider text-[#FCCA00] no-underline transition hover:bg-[#FCCA00]/10 sm:inline-flex">
                                {t('Iniciar sesion')}
                            </Link>
                            <Link href={routes.register || '/register'} className="rounded-full bg-[#FCCA00] px-5 py-3 text-xs font-black uppercase tracking-wider text-black no-underline shadow-[0_12px_34px_rgba(252,202,0,.28)] transition hover:bg-yellow-300 sm:px-7">
                                {t('Registrarse')}
                            </Link>
                        </>
                    )}
                </div>
            </div>
        </header>
    );
}

function MenuLink({ href, icon, label, onClick }) {
    return (
        <Link href={href} onClick={onClick} className="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-black text-white/78 no-underline transition hover:bg-white/[0.06] hover:text-[#FCCA00]">
            <i className={`fas ${icon} w-5 text-center text-[#FCCA00]`} />
            {label}
        </Link>
    );
}

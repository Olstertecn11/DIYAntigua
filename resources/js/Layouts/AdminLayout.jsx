import { Link, router, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';

const adminItems = [
    { label: 'Dashboard', icon: 'fa-grid-2', route: 'dashboard' },
    { label: 'Usuarios', icon: 'fa-user-gear', route: 'usuarios', section: 'Administracion' },
    { label: 'Reservas', icon: 'fa-calendar-check', route: 'reservas', section: 'Operaciones' },
    { label: 'Rutas y Tarifas', icon: 'fa-route', route: 'rutas' },
    { label: 'Lugares / Hoteles', icon: 'fa-map-location-dot', route: 'lugares' },
    { label: 'Vehiculos', icon: 'fa-truck-fast', route: 'vehiculos' },
    { label: 'Conductores', icon: 'fa-id-card', route: 'conductores' },
    { label: 'Afiliados', icon: 'fa-users', route: 'afiliados', section: 'Negocio' },
    { label: 'Pagos y Comis.', icon: 'fa-wallet', route: 'pagos' },
];

function NavItem({ href, active, icon, children }) {
    return (
        <li>
            <Link
                href={href}
                className={[
                    'flex flex-col md:flex-row items-center justify-between p-2 md:px-4 md:py-3 transition-all duration-200 group rounded-xl',
                    active
                        ? 'bg-white/10 text-white shadow-[0_0_15px_rgba(255,255,255,0.05)]'
                        : 'text-[#737373] hover:bg-white/5 hover:text-white',
                ].join(' ')}
            >
                <div className="flex items-center">
                    <i className={`fas ${icon} text-lg md:mr-4 opacity-70 transition-transform group-hover:scale-110`} />
                    <span className="text-[10px] md:text-sm font-semibold tracking-wide">{children}</span>
                </div>
                {active && <div className="hidden md:block w-1.5 h-1.5 rounded-full bg-white shadow-[0_0_8px_white]" />}
            </Link>
        </li>
    );
}

function FlashMessages({ flash, errors }) {
    const [visible, setVisible] = useState(true);
    const errorMessages = Object.values(errors || {});

    useEffect(() => {
        setVisible(true);
        const timeout = setTimeout(() => setVisible(false), 5000);

        return () => clearTimeout(timeout);
    }, [flash?.success, flash?.error, errorMessages.join('|')]);

    if (!visible || (!flash?.success && !flash?.error && errorMessages.length === 0)) {
        return null;
    }

    const messages = [
        ...errorMessages.map((message) => ({ type: 'error', message })),
        flash?.error ? { type: 'error', message: flash.error } : null,
        flash?.success ? { type: 'success', message: flash.success } : null,
    ].filter(Boolean);

    return (
        <div className="fixed right-4 top-4 z-[100] w-[min(360px,calc(100vw-32px))] space-y-3">
            {messages.map((item, index) => (
                <div
                    key={`${item.type}-${index}`}
                    className="flex items-center gap-3 rounded-xl border border-white/10 bg-black/90 px-4 py-3 text-white shadow-2xl"
                >
                    <span className={[
                        'flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs',
                        item.type === 'success' ? 'bg-green-500' : 'bg-red-500',
                    ].join(' ')}
                    >
                        <i className={`fas ${item.type === 'success' ? 'fa-check' : 'fa-exclamation'}`} />
                    </span>
                    <span className="text-sm font-bold leading-snug">{item.message}</span>
                    <button
                        type="button"
                        onClick={() => setVisible(false)}
                        className="ml-auto h-7 w-7 rounded-full bg-white/10 text-xs text-white/70 transition hover:bg-white/20 hover:text-white"
                        aria-label="Cerrar"
                    >
                        <i className="fas fa-times" />
                    </button>
                </div>
            ))}
        </div>
    );
}

export default function AdminLayout({ children }) {
    const { props, url } = usePage();
    const user = props.auth?.user;
    const routes = props.routes || {};
    const routeGroup = routes.admin;
    const profileUrl = routes.admin?.profile;
    const logoutUrl = routes.admin?.logout;

    const logout = () => {
        router.post(logoutUrl);
    };

    return (
        <div className="min-h-screen bg-[#0f1115] text-white">
            <FlashMessages flash={props.flash} errors={props.errors} />
            <div className="flex min-h-screen flex-col bg-[#0f1115] md:flex-row">
                <aside className="fixed bottom-0 z-50 h-16 w-full border-t border-white/5 bg-[#0a0a0a] shadow-2xl transition-all duration-300 md:relative md:h-screen md:w-64 md:border-r md:border-t-0 md:border-[#262626]">
                    <div className="flex h-full flex-col md:fixed md:left-0 md:top-0 md:w-64">
                        <div className="hidden items-center border-b border-[#262626] p-8 md:flex">
                            <div className="mr-3 flex h-8 w-8 items-center justify-center rounded-lg bg-white shadow-[0_0_20px_rgba(255,255,255,0.1)]">
                                <span className="text-lg font-black italic text-black">A</span>
                            </div>
                            <div className="flex flex-col">
                                <span className="text-sm font-bold leading-none tracking-tighter text-white">ANTIGUA</span>
                                <span className="text-[10px] font-medium uppercase tracking-widest text-[#737373]">Transfers</span>
                            </div>
                        </div>

                        <nav className="flex-grow overflow-y-auto pt-6">
                            <ul className="flex flex-row space-y-0 px-4 md:flex-col md:space-y-1">
                                {adminItems.map((item) => (
                                    <div key={item.label} className="contents">
                                        {item.section && (
                                            <li className="hidden px-4 pb-2 pt-4 md:block">
                                                <span className="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#404040]">
                                                    {item.section}
                                                </span>
                                            </li>
                                        )}
                                        <NavItem
                                            href={routeGroup?.[item.route] || '#'}
                                            icon={item.icon}
                                            active={Boolean(routeGroup?.[item.route] && url.startsWith(new URL(routeGroup[item.route], window.location.origin).pathname))}
                                        >
                                            {item.label}
                                        </NavItem>
                                    </div>
                                ))}
                            </ul>
                        </nav>

                        {user && (
                            <div className="hidden border-t border-[#262626] bg-[#050505] p-4 md:block">
                                <div className="mb-4 flex items-center px-2">
                                    <div className="flex h-8 w-8 items-center justify-center rounded-full border border-[#262626] bg-[#171717] text-xs font-bold text-white">
                                        {user.name?.charAt(0)}
                                    </div>
                                    <div className="ml-3 overflow-hidden">
                                        <p className="mb-0 truncate text-[11px] font-bold text-white">{user.name}</p>
                                        <p className="mb-0 text-[9px] font-medium uppercase tracking-tighter text-[#737373]">
                                            Administrator
                                        </p>
                                    </div>
                                </div>

                                <Link
                                    href={profileUrl || '#'}
                                    className="mb-3 flex items-center justify-center rounded-lg border border-white/10 py-2 text-[10px] font-bold uppercase tracking-widest text-[#a1a1a1] transition hover:border-[#fcca00]/40 hover:text-[#fcca00]"
                                >
                                    <i className="fas fa-user-gear mr-2 text-[8px]" />
                                    Perfil
                                </Link>

                                <button
                                    type="button"
                                    onClick={logout}
                                    className="flex w-full items-center justify-center rounded-lg border border-transparent py-2 text-[10px] font-bold uppercase tracking-widest text-[#a1a1a1] transition-all hover:border-red-500/20 hover:bg-red-500/10 hover:text-white"
                                >
                                    <i className="fas fa-power-off mr-2 text-[8px]" />
                                    Salir del Sistema
                                </button>
                            </div>
                        )}
                    </div>
                </aside>

                <main className="flex-1 bg-black pb-24 md:pb-5">
                    {children}
                </main>
            </div>
        </div>
    );
}

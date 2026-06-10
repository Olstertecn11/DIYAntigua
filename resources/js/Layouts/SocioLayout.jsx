import { Link, router, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';

const items = [
    { label: 'Mi Panel', icon: 'fa-home', route: 'dashboard' },
    { label: 'Mi Perfil', icon: 'fa-user-gear', route: 'profile' },
];

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
                <div key={`${item.type}-${index}`} className="flex items-center gap-3 rounded-xl border border-white/10 bg-black/90 px-4 py-3 text-white shadow-2xl">
                    <span className={['flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs', item.type === 'success' ? 'bg-green-500' : 'bg-red-500'].join(' ')}>
                        <i className={`fas ${item.type === 'success' ? 'fa-check' : 'fa-exclamation'}`} />
                    </span>
                    <span className="text-sm font-bold leading-snug">{item.message}</span>
                    <button type="button" onClick={() => setVisible(false)} className="ml-auto h-7 w-7 rounded-full bg-white/10 text-xs text-white/70 transition hover:bg-white/20 hover:text-white" aria-label="Cerrar">
                        <i className="fas fa-times" />
                    </button>
                </div>
            ))}
        </div>
    );
}

export default function SocioLayout({ children }) {
    const { props, url } = usePage();
    const user = props.auth?.user;
    const routes = props.routes?.socios || {};

    const logout = () => {
        router.post(props.routes?.logout);
    };

    return (
        <div className="min-h-screen bg-black text-white">
            <FlashMessages flash={props.flash} errors={props.errors} />
            <aside className="fixed bottom-0 z-50 h-16 w-full border-t border-white/10 bg-[#080808] md:bottom-auto md:left-0 md:top-0 md:h-screen md:w-64 md:border-r md:border-t-0">
                <div className="flex h-full flex-col">
                    <div className="hidden border-b border-white/10 p-7 md:block">
                        <div className="flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-[#fcca00] text-lg font-black text-black">S</div>
                            <div>
                                <p className="mb-0 text-sm font-black uppercase tracking-tight">Socios</p>
                                <p className="mb-0 text-[10px] font-bold uppercase tracking-widest text-white/40">DYANTIGUA</p>
                            </div>
                        </div>
                    </div>

                    <nav className="flex flex-1 items-center px-3 md:items-start md:pt-6">
                        <ul className="grid w-full grid-cols-2 gap-2 md:grid-cols-1">
                            {items.map((item) => {
                                const href = routes[item.route] || '#';
                                const active = href !== '#' && url.startsWith(new URL(href, window.location.origin).pathname);

                                return (
                                    <li key={item.route}>
                                        <Link
                                            href={href}
                                            className={[
                                                'flex h-12 items-center justify-center gap-2 rounded-xl px-3 text-[11px] font-black uppercase tracking-widest no-underline transition md:justify-start md:text-xs',
                                                active ? 'bg-[#fcca00] text-black' : 'text-white/55 hover:bg-white/5 hover:text-white',
                                            ].join(' ')}
                                        >
                                            <i className={`fas ${item.icon}`} />
                                            {item.label}
                                        </Link>
                                    </li>
                                );
                            })}
                        </ul>
                    </nav>

                    <div className="hidden border-t border-white/10 p-4 md:block">
                        <div className="mb-4 flex items-center gap-3 rounded-xl bg-white/5 p-3">
                            <div className="flex h-9 w-9 items-center justify-center rounded-full bg-white/10 text-xs font-black">
                                {user?.name?.charAt(0)}
                            </div>
                            <div className="min-w-0">
                                <p className="mb-0 truncate text-xs font-black">{user?.name}</p>
                                <p className="mb-0 truncate text-[10px] font-bold text-white/40">{user?.email}</p>
                            </div>
                        </div>
                        <button type="button" onClick={logout} className="w-full rounded-xl border border-red-500/20 px-4 py-3 text-xs font-black uppercase tracking-widest text-red-200 transition hover:bg-red-500/10">
                            Salir
                        </button>
                    </div>
                </div>
            </aside>
            <main className="min-h-screen pb-20 md:ml-64 md:pb-0">
                {children}
            </main>
        </div>
    );
}

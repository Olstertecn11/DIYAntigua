import FloatingActions from '@/Components/Public/FloatingActions';
import PublicFooter from '@/Components/Public/PublicFooter';
import PublicNavbar from '@/Components/Public/PublicNavbar';
import { usePage } from '@inertiajs/react';
import { useEffect, useMemo, useState } from 'react';

export default function PublicLayout({ children }) {
    const { props } = usePage();
    const [visible, setVisible] = useState(false);
    const notice = useMemo(() => {
        if (props.flash?.success) {
            return { type: 'success', message: props.flash.success };
        }

        if (props.flash?.error) {
            return { type: 'error', message: props.flash.error };
        }

        return null;
    }, [props.flash?.error, props.flash?.success]);

    useEffect(() => {
        if (!notice?.message) {
            return undefined;
        }

        setVisible(true);
        const timer = window.setTimeout(() => setVisible(false), 5200);
        return () => window.clearTimeout(timer);
    }, [notice]);

    return (
        <div className="min-h-screen bg-white text-slate-950">
            <PublicNavbar />
            {notice?.message && visible && (
                <div className="fixed right-4 top-28 z-[140] w-[calc(100%-2rem)] max-w-sm animate-[toast-in_.24s_ease-out] rounded-2xl border border-black/10 bg-white p-4 shadow-[0_24px_70px_rgba(0,0,0,.18)]">
                    <div className="flex gap-3">
                        <span className={[
                            'flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-black',
                            notice.type === 'success' ? 'bg-[#FCCA00]' : 'bg-red-100 text-red-700',
                        ].join(' ')}
                        >
                            <i className={`fas ${notice.type === 'success' ? 'fa-check' : 'fa-circle-exclamation'}`} />
                        </span>
                        <div className="min-w-0">
                            <p className="mb-1 text-[10px] font-black uppercase tracking-[0.18em] text-slate-500">{notice.type === 'success' ? 'Listo' : 'Atencion'}</p>
                            <p className="mb-0 text-sm font-bold text-slate-900">{notice.message}</p>
                        </div>
                        <button type="button" onClick={() => setVisible(false)} className="ml-auto h-8 w-8 shrink-0 rounded-full bg-slate-100 text-slate-500">
                            <i className="fas fa-times text-xs" />
                        </button>
                    </div>
                </div>
            )}
            {children}
            <PublicFooter />
            <FloatingActions />
        </div>
    );
}

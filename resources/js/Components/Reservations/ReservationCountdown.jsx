import { useEffect, useMemo, useState } from 'react';

function remaining(target) {
    const diff = Math.max(0, new Date(target).getTime() - Date.now());

    return {
        total: diff,
        days: Math.floor(diff / 86400000),
        hours: Math.floor((diff % 86400000) / 3600000),
        minutes: Math.floor((diff % 3600000) / 60000),
        seconds: Math.floor((diff % 60000) / 1000),
    };
}

export default function ReservationCountdown({ travelIso, status, travelAt, dark = false, compact = false }) {
    const [time, setTime] = useState(() => remaining(travelIso));
    const inactive = ['cancelado', 'completado'].includes(status);

    useEffect(() => {
        if (inactive) return undefined;

        const update = () => setTime(remaining(travelIso));
        update();
        const timer = window.setInterval(update, 1000);
        return () => window.clearInterval(timer);
    }, [inactive, travelIso]);

    const state = useMemo(() => {
        if (status === 'cancelado') return { label: 'Reserva cancelada', icon: 'fa-ban' };
        if (status === 'completado') return { label: 'Viaje completado', icon: 'fa-circle-check' };
        if (status === 'en_progreso' || time.total === 0) return { label: 'Viaje en curso', icon: 'fa-route' };
        return null;
    }, [status, time.total]);

    const surface = dark
        ? 'border-white/10 bg-white/[0.07] text-white'
        : 'border-black/10 bg-white/75 text-black';

    return (
        <section className={`overflow-hidden rounded-[1.75rem] border p-5 shadow-[0_22px_60px_rgba(0,0,0,.12)] backdrop-blur-xl ${surface}`}>
            <div className="flex items-center justify-between gap-4">
                <div>
                    <p className={`mb-1 text-[10px] font-black uppercase tracking-[0.2em] ${dark ? 'text-white/45' : 'text-black/45'}`}>Tiempo para el viaje</p>
                    <p className="mb-0 text-xs font-bold opacity-65">{travelAt}</p>
                </div>
                <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-[#FCCA00] text-black shadow-[0_10px_30px_rgba(252,202,0,.3)]">
                    <i className={`fas ${state?.icon || 'fa-clock'}`} />
                </span>
            </div>

            {state ? (
                <div className="mt-5 rounded-2xl border border-[#FCCA00]/25 bg-[#FCCA00]/10 px-4 py-5 text-center text-xl font-black text-[#FCCA00]">{state.label}</div>
            ) : (
                <div className={`mt-5 grid gap-2 ${compact ? 'grid-cols-4' : 'grid-cols-2 sm:grid-cols-4'}`}>
                    <Unit value={time.days} label="Días" dark={dark} />
                    <Unit value={time.hours} label="Horas" dark={dark} />
                    <Unit value={time.minutes} label="Min" dark={dark} />
                    <Unit value={time.seconds} label="Seg" dark={dark} />
                </div>
            )}
        </section>
    );
}

function Unit({ value, label, dark }) {
    return (
        <div className={`rounded-2xl border px-2 py-3 text-center ${dark ? 'border-white/10 bg-black/25' : 'border-black/5 bg-black/[0.035]'}`}>
            <strong className="block text-2xl font-black tabular-nums text-[#FCCA00]">{String(value).padStart(2, '0')}</strong>
            <span className={`mt-1 block text-[9px] font-black uppercase tracking-widest ${dark ? 'text-white/45' : 'text-black/45'}`}>{label}</span>
        </div>
    );
}

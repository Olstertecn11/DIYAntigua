import { Head, Link, router } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import PublicLayout from '@/Layouts/PublicLayout';

function money(value) {
    return `Q${Number(value || 0).toLocaleString('es-GT', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

export default function UserReservationShow({ reserva, urls }) {
    const [countdown, setCountdown] = useState(reserva.estado_viaje === 'cancelado' ? 'Cancelada' : 'Calculando...');
    const [openCancel, setOpenCancel] = useState(false);
    const [motivo, setMotivo] = useState('');

    useEffect(() => {
        if (reserva.estado_viaje === 'cancelado') return undefined;
        const update = () => {
            const diff = new Date(reserva.travel_iso).getTime() - Date.now();
            if (diff <= 0) {
                setCountdown('En curso');
                return;
            }
            const days = Math.floor(diff / 86400000);
            const hours = Math.floor((diff % 86400000) / 3600000);
            const minutes = Math.floor((diff % 3600000) / 60000);
            setCountdown(`${days}d ${hours}h ${minutes}m`);
        };
        update();
        const timer = setInterval(update, 60000);
        return () => clearInterval(timer);
    }, [reserva.estado_viaje, reserva.travel_iso]);

    const cancel = (event) => {
        event.preventDefault();
        router.post(urls.cancel, { motivo_cancelacion: motivo }, { preserveScroll: true, onSuccess: () => setOpenCancel(false) });
    };

    return (
        <PublicLayout>
            <Head title={reserva.codigo_reserva} />
            <main className="min-h-screen bg-[#f7f7f2] pb-12 pt-36">
                <div className="mx-auto max-w-6xl px-4">
                    <Link href={urls.index} className="text-xs font-black uppercase tracking-widest text-[#363636] no-underline"><i className="fas fa-arrow-left mr-2" />Mis reservaciones</Link>
                    <section className="mt-5 flex flex-col justify-between gap-6 rounded-[28px] border border-black/10 bg-white p-8 shadow-[0_24px_70px_rgba(0,0,0,.08)] lg:flex-row lg:items-center">
                        <div><span className="text-xs font-black uppercase tracking-[.16em] text-[#b89500]">Mi reservacion</span><h1 className="my-2 text-5xl font-black text-black">{reserva.codigo_reserva}</h1><p className="m-0 text-lg font-bold text-[#666]">{reserva.ruta.label}</p></div>
                        <div className="min-w-[220px] rounded-[22px] bg-black p-6 text-center text-white"><span className="text-xs font-black uppercase tracking-widest text-[#777]">Tiempo restante</span><strong className="my-2 block text-3xl font-black text-[#FCCA00]">{countdown}</strong><small>{reserva.travel_at}</small></div>
                    </section>
                    <div className="mt-6 grid gap-6 lg:grid-cols-[1fr_360px]">
                        <section className="rounded-[28px] border border-black/10 bg-white p-7 shadow-[0_24px_70px_rgba(0,0,0,.08)]">
                            <h2 className="mb-5 text-2xl font-black text-black">Detalles del traslado</h2>
                            <div className="grid gap-5 md:grid-cols-2">
                                <Detail label="Pasajero" value={reserva.nombre_cliente} />
                                <Detail label="Telefono" value={reserva.telefono_cliente} />
                                <Detail label="Correo" value={reserva.correo_cliente} />
                                <Detail label="Fecha" value={reserva.travel_date} />
                                <Detail label="Hora" value={reserva.travel_time} />
                                <Detail label="Vehiculo" value={String(reserva.tipo_vehiculo || '').toUpperCase()} />
                                <Detail label="Pasajeros" value={reserva.pasajeros} />
                                <Detail label="Total" value={money(reserva.precio_total)} />
                            </div>
                            <div className="mt-6 rounded-2xl border border-black/10 bg-[#f6f6f3] p-5"><span className="text-xs font-black uppercase tracking-widest text-[#777]">Notas y puntos</span><p className="mt-2 whitespace-pre-wrap text-[#363636]">{reserva.notas_adicionales}</p></div>
                        </section>
                        <aside className="rounded-[28px] border border-black/10 bg-white p-7 shadow-[0_24px_70px_rgba(0,0,0,.08)]">
                            <h2 className="mb-5 text-2xl font-black text-black">Estado</h2>
                            <div className="grid gap-2"><Status text={`Pago: ${reserva.estado_pago}`} /><Status text={`Viaje: ${reserva.estado_viaje}`} /><Status text={`Reembolso: ${reserva.reembolso_estado || 'sin solicitud'}`} /></div>
                            {reserva.transaction && <div className="mt-5 rounded-2xl border border-black/10 bg-[#f6f6f3] p-5"><span className="text-xs font-black uppercase tracking-widest text-[#777]">Referencia</span><strong className="mt-2 block break-all text-black">{reserva.transaction.reference}</strong><small>{String(reserva.transaction.card_brand).toUpperCase()} **** {reserva.transaction.card_last_four}</small></div>}
                            <div className={`mt-5 rounded-2xl p-5 ${reserva.can_cancel ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`}><strong className="font-black">{reserva.can_cancel ? 'Cancelable con reembolso' : 'Cancelacion cerrada'}</strong><p className="mb-0 mt-1 text-sm font-bold">{reserva.can_cancel ? 'Puedes cancelar porque faltan mas de 24 horas para el viaje.' : 'Ya no cumple la politica de mas de 24 horas antes del viaje.'}</p></div>
                            <div className="mt-5 grid gap-3"><a href={urls.pdf} className="rounded-full bg-[#FCCA00] px-5 py-3 text-center text-xs font-black uppercase tracking-widest text-black no-underline">Descargar comprobante</a>{reserva.can_cancel && <button type="button" onClick={() => setOpenCancel(true)} className="rounded-full border-0 bg-red-600 px-5 py-3 text-xs font-black uppercase tracking-widest text-white">Cancelar reservacion</button>}</div>
                        </aside>
                    </div>
                </div>
            </main>
            {openCancel && (
                <div className="fixed inset-0 z-[120] flex items-center justify-center bg-black/70 p-4 backdrop-blur">
                    <div className="w-full max-w-xl rounded-[28px] bg-white p-7 shadow-2xl">
                        <h2 className="text-2xl font-black text-black">Cancelar reservacion</h2>
                        <p className="text-[#666]">La reserva se cancelara y el reembolso quedara en revision administrativa si aplica.</p>
                        <form onSubmit={cancel} className="mt-4">
                            <textarea value={motivo} onChange={(event) => setMotivo(event.target.value)} maxLength="500" rows="3" placeholder="Motivo opcional" className="w-full resize-y rounded-2xl border border-black/10 p-3" />
                            <div className="mt-4 grid grid-cols-2 gap-3"><button type="button" onClick={() => setOpenCancel(false)} className="rounded-full border border-black/10 bg-white px-5 py-3 text-xs font-black uppercase">Volver</button><button className="rounded-full border-0 bg-red-600 px-5 py-3 text-xs font-black uppercase text-white">Confirmar cancelacion</button></div>
                        </form>
                    </div>
                </div>
            )}
        </PublicLayout>
    );
}

function Detail({ label, value }) {
    return <div><span className="block text-xs font-black uppercase tracking-widest text-[#777]">{label}</span><strong className="mt-1 block break-words text-black">{value || 'N/A'}</strong></div>;
}

function Status({ text }) {
    return <span className="rounded-full bg-[#f6f6f3] px-3 py-2 text-xs font-black uppercase text-[#363636]">{text}</span>;
}

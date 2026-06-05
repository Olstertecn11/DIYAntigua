import { Link } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';

function money(value) {
    return `Q${Number(value || 0).toFixed(2)}`;
}

export default function Result({ status, message, transaction, reserva, urls = {} }) {
    const approved = status === 'approved';
    const declined = status === 'declined';
    const title = approved ? 'Pago aprobado' : declined ? 'Pago rechazado' : 'Pago en revision';
    const copy = approved
        ? 'La reserva fue creada y el pago fue confirmado correctamente.'
        : declined
            ? 'La reserva fue creada, pero el cobro no fue aprobado por el procesador.'
            : 'La reserva fue creada y el pago requiere revision o un nuevo intento.';

    return (
        <PublicLayout>
        <main className="min-h-screen bg-[linear-gradient(135deg,#fff_0%,#f7f7f2_60%,#ecebe5_100%)] px-4 pb-12 pt-36 text-slate-950 sm:px-6">
            <div className="mx-auto max-w-5xl rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
                <div className="mb-8 flex flex-col justify-between gap-6 lg:flex-row">
                    <div>
                        <span className={approved ? 'rounded-full bg-green-100 px-4 py-2 text-xs font-black uppercase tracking-widest text-green-700' : 'rounded-full bg-yellow-100 px-4 py-2 text-xs font-black uppercase tracking-widest text-yellow-700'}>
                            {String(status).toUpperCase()}
                        </span>
                        <h1 className="mb-3 mt-5 text-4xl font-black">{title}</h1>
                        <p className="mb-0 max-w-2xl text-slate-600">{message || copy}</p>
                    </div>
                    <div className="rounded-2xl bg-slate-50 p-4 text-center">
                        <img src={reserva.qr_url} alt="QR de reserva" className="mx-auto mb-2 h-36 w-36" />
                        <strong>{reserva.codigo_reserva}</strong>
                    </div>
                </div>

                <div className="mb-8 grid gap-3 md:grid-cols-2">
                    {[
                        ['Origen', reserva.ruta.origen],
                        ['Destino', reserva.ruta.destino],
                        ['Cliente', reserva.nombre_cliente],
                        ['Reserva', reserva.codigo_reserva],
                        ['Fecha', reserva.fecha_viaje],
                        ['Hora', reserva.hora_viaje],
                        ['Vehiculo', reserva.tipo_vehiculo],
                        ['Pasajeros', reserva.pasajeros],
                    ].map(([label, value]) => (
                        <div key={label} className="rounded-2xl bg-slate-50 p-4">
                            <span className="text-xs font-black uppercase tracking-widest text-slate-500">{label}</span>
                            <strong className="mt-1 block">{value}</strong>
                        </div>
                    ))}
                </div>

                <div className="mb-8 grid gap-3 rounded-[1.5rem] bg-black p-5 text-white md:grid-cols-3">
                    <div>
                        <span className="text-xs font-black uppercase tracking-widest text-white/40">Monto</span>
                        <strong className="block text-xl">{money(transaction.amount)}</strong>
                    </div>
                    <div>
                        <span className="text-xs font-black uppercase tracking-widest text-white/40">Transaccion</span>
                        <strong className="block break-words">{transaction.provider_transaction_id || transaction.reference}</strong>
                    </div>
                    <div>
                        <span className="text-xs font-black uppercase tracking-widest text-white/40">Tarjeta</span>
                        <strong className="block">{String(transaction.card_brand || 'CARD').toUpperCase()} **** {transaction.card_last_four || '----'}</strong>
                    </div>
                </div>

                <div className="flex flex-col gap-3 sm:flex-row">
                    {!approved && <Link href={urls.checkout} className="rounded-2xl bg-yellow-400 px-5 py-3 text-center text-sm font-black uppercase tracking-widest text-black no-underline">Intentar pago nuevamente</Link>}
                    <Link href={urls.reservation} className="rounded-2xl bg-slate-950 px-5 py-3 text-center text-sm font-black uppercase tracking-widest text-white no-underline">Ver reserva</Link>
                    <a href={urls.pdf} className="rounded-2xl border border-slate-200 px-5 py-3 text-center text-sm font-black uppercase tracking-widest text-slate-700 no-underline">Descargar PDF</a>
                </div>
            </div>
        </main>
        </PublicLayout>
    );
}

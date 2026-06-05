import { Head, Link, useForm } from '@inertiajs/react';
import PublicLayout from '@/Layouts/PublicLayout';
import { useEffect } from 'react';

function money(value) {
    return `Q${Number(value || 0).toFixed(2)}`;
}

function Field({ label, name, data, setData, errors, type = 'text', placeholder, autoComplete, maxLength }) {
    return (
        <div>
            <label className="mb-2 block text-sm font-bold text-white/60">{label}</label>
            <input
                type={type}
                value={data[name] || ''}
                onChange={(event) => setData(name, event.target.value)}
                required
                placeholder={placeholder || label}
                autoComplete={autoComplete}
                maxLength={maxLength}
                className="w-full rounded-2xl border border-white/10 bg-black px-4 py-3 text-sm font-bold text-white outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-400/10"
            />
            {errors[name] && <p className="mt-2 text-sm font-bold text-red-300">{errors[name]}</p>}
        </div>
    );
}

export default function Checkout({ reserva, defaults = {}, fingerprint = {}, urls = {} }) {
    const { data, setData, post, processing, errors } = useForm({
        cc_name: defaults.cc_name || '',
        cc_number: '',
        cc_exp_month: defaults.cc_exp_month || '',
        cc_exp_year: defaults.cc_exp_year || '',
        cc_cvv2: '',
        cc_type: defaults.cc_type || 'visa',
        billing_address: defaults.billing_address || 'Guatemala',
        billing_city: defaults.billing_city || 'Guatemala',
        billing_state: defaults.billing_state || 'Guatemala',
        billing_country: defaults.billing_country || 'Guatemala',
        billing_zip: defaults.billing_zip || '01001',
        fingerprint_session_id: fingerprint.sessionId || '',
        finger: '',
    });

    useEffect(() => {
        window.initFingerprintJS = async function initFingerprintJS() {
            try {
                if (!window.FingerprintJS) {
                    return;
                }

                const fp = await window.FingerprintJS.load();
                const result = await fp.get();
                setData('finger', result.visitorId || '');
            } catch (error) {
                setData('finger', '');
            }
        };

        const existingScript = document.querySelector('script[data-fingerprint-js]');
        const script = existingScript || document.createElement('script');

        if (!existingScript) {
            script.async = true;
            script.src = 'https://fpcdn.io/v3/cUXvdB6hHu/iife.min.js';
            script.dataset.fingerprintJs = 'true';
            document.head.appendChild(script);
        }

        script.addEventListener('load', window.initFingerprintJS);

        return () => {
            script.removeEventListener('load', window.initFingerprintJS);
            delete window.initFingerprintJS;
        };
    }, [setData]);

    const submit = (event) => {
        event.preventDefault();
        post(urls.store);
    };

    return (
        <PublicLayout>
        <main className="min-h-screen bg-black px-4 pb-12 pt-36 text-white sm:px-6">
            <Head>
                {fingerprint.orgId && fingerprint.fullSessionId && (
                    <script src={`https://h.online-metrix.net/fp/tags.js?org_id=${fingerprint.orgId}&session_id=${fingerprint.fullSessionId}`} type="application/javascript" />
                )}
            </Head>

            <div className="mx-auto grid max-w-6xl gap-8 lg:grid-cols-[1fr_360px]">
                <section className="rounded-[1.75rem] border border-white/10 bg-[#111] p-6 shadow-2xl lg:p-8">
                    <p className="mb-3 text-xs font-black uppercase tracking-[0.2em] text-yellow-300">Pago seguro</p>
                    <h1 className="mb-2 text-4xl font-black">Completa el pago de tu reserva</h1>
                    <p className="mb-8 text-white/60">Reserva {reserva.codigo_reserva}</p>

                    <form onSubmit={submit} className="space-y-5">
                        <Field label="Nombre en la tarjeta" name="cc_name" data={data} setData={setData} errors={errors} autoComplete="cc-name" maxLength="120" />
                        <Field label="Numero de tarjeta" name="cc_number" data={data} setData={setData} errors={errors} autoComplete="cc-number" maxLength="23" />
                        <div className="grid gap-4 md:grid-cols-3">
                            <Field label="Mes" name="cc_exp_month" data={data} setData={setData} errors={errors} placeholder="MM" maxLength="2" />
                            <Field label="Ano" name="cc_exp_year" data={data} setData={setData} errors={errors} placeholder="YYYY" maxLength="4" />
                            <Field label="CVV" name="cc_cvv2" data={data} setData={setData} errors={errors} type="password" autoComplete="cc-csc" maxLength="4" />
                        </div>
                        <div>
                            <label className="mb-2 block text-sm font-bold text-white/60">Tipo de tarjeta</label>
                            <select
                                value={data.cc_type}
                                onChange={(event) => setData('cc_type', event.target.value)}
                                required
                                className="w-full rounded-2xl border border-white/10 bg-black px-4 py-3 text-sm font-bold text-white outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-400/10"
                            >
                                <option value="visa">Visa</option>
                                <option value="mastercard">Mastercard</option>
                            </select>
                            {errors.cc_type && <p className="mt-2 text-sm font-bold text-red-300">{errors.cc_type}</p>}
                        </div>
                        <Field label="Direccion" name="billing_address" data={data} setData={setData} errors={errors} maxLength="191" />
                        <div className="grid gap-4 md:grid-cols-2">
                            <Field label="Ciudad" name="billing_city" data={data} setData={setData} errors={errors} maxLength="100" />
                            <Field label="Departamento" name="billing_state" data={data} setData={setData} errors={errors} maxLength="100" />
                            <Field label="Pais" name="billing_country" data={data} setData={setData} errors={errors} maxLength="100" />
                            <Field label="Codigo postal" name="billing_zip" data={data} setData={setData} errors={errors} maxLength="20" />
                        </div>
                        {(errors.reserva || errors.payment || errors.message) && (
                            <p className="rounded-2xl bg-red-500/10 p-4 text-sm font-bold text-red-200">
                                {errors.reserva || errors.payment || errors.message}
                            </p>
                        )}
                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full rounded-2xl bg-yellow-400 px-6 py-4 text-sm font-black uppercase tracking-widest text-black transition hover:bg-yellow-300 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {processing ? 'Procesando...' : `Pagar ${money(reserva.precio_total)}`}
                        </button>
                    </form>
                </section>

                <aside className="h-fit rounded-[1.75rem] border border-white/10 bg-[#111] p-6 lg:sticky lg:top-6">
                    <h2 className="mb-5 text-2xl font-black">Resumen</h2>
                    <div className="space-y-3">
                        {[
                            ['Codigo', reserva.codigo_reserva],
                            ['Cliente', reserva.nombre_cliente],
                            ['Fecha', `${reserva.fecha_viaje} ${reserva.hora_viaje}`],
                            ['Total', money(reserva.precio_total)],
                        ].map(([label, value]) => (
                            <div key={label} className="flex items-center justify-between gap-4 rounded-2xl bg-black p-4">
                                <span className="text-xs font-black uppercase tracking-widest text-white/40">{label}</span>
                                <strong className="text-right">{value}</strong>
                            </div>
                        ))}
                    </div>
                    <Link href={urls.reservation || '#'} className="mt-5 block rounded-2xl border border-white/10 px-5 py-3 text-center text-sm font-black uppercase tracking-widest text-white/70 no-underline">
                        Ver reserva
                    </Link>
                </aside>
            </div>
        </main>
        </PublicLayout>
    );
}

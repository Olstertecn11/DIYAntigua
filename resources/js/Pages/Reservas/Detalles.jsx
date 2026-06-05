import { Head, Link, useForm } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useMemo, useState } from 'react';

function money(value) {
    return `Q${Number(value || 0).toFixed(2)}`;
}

function FieldError({ message }) {
    if (!message) {
        return null;
    }

    return <p className="mt-2 text-sm font-bold text-red-600">{message}</p>;
}

function TextInput({ label, name, data, setData, errors, type = 'text', required = true, autoComplete, placeholder }) {
    return (
        <div>
            <label className="mb-2 block text-sm font-black text-slate-700">{label}</label>
            <input
                type={type}
                value={data[name] || ''}
                onChange={(event) => setData(name, event.target.value)}
                required={required}
                autoComplete={autoComplete}
                placeholder={placeholder || label}
                className={[
                    'w-full rounded-2xl border bg-white px-4 py-3 text-sm font-bold text-slate-900 shadow-sm outline-none transition focus:ring-4',
                    errors[name] ? 'border-red-400 focus:border-red-500 focus:ring-red-100' : 'border-slate-200 focus:border-yellow-400 focus:ring-yellow-100',
                ].join(' ')}
            />
            <FieldError message={errors[name]} />
        </div>
    );
}

function TextArea({ label, name, data, setData, errors, required = true, placeholder }) {
    return (
        <div>
            <label className="mb-2 block text-sm font-black text-slate-700">{label}</label>
            <textarea
                value={data[name] || ''}
                onChange={(event) => setData(name, event.target.value)}
                required={required}
                rows="3"
                placeholder={placeholder || label}
                className={[
                    'w-full rounded-2xl border bg-white px-4 py-3 text-sm font-bold text-slate-900 shadow-sm outline-none transition focus:ring-4',
                    errors[name] ? 'border-red-400 focus:border-red-500 focus:ring-red-100' : 'border-slate-200 focus:border-yellow-400 focus:ring-yellow-100',
                ].join(' ')}
            />
            <FieldError message={errors[name]} />
        </div>
    );
}

function Panel({ title, kicker, children }) {
    return (
        <section className="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
            <div className="mb-5">
                {kicker && <p className="mb-2 text-xs font-black uppercase tracking-[0.2em] text-yellow-600">{kicker}</p>}
                <h2 className="mb-0 text-2xl font-black">{title}</h2>
            </div>
            {children}
        </section>
    );
}

export default function Detalles({
    ruta,
    datos,
    vehiculoSeleccionado,
    countries = [],
    defaults = {},
    paymentDefaults = {},
    fingerprint = {},
    authenticatedEmail = null,
    urls = {},
}) {
    const [emailModalOpen, setEmailModalOpen] = useState(false);
    const [emailCode, setEmailCode] = useState('');
    const [emailMessage, setEmailMessage] = useState('');
    const [emailBusy, setEmailBusy] = useState(false);

    const defaultCountry = useMemo(() => countries.find((country) => country.code === 'GT') || countries[0], [countries]);
    const { data, setData, post, processing, errors, transform } = useForm({
        ruta_id: ruta.id,
        vehiculo_id: datos.vehiculo_id,
        fecha_viaje: datos.fecha,
        hora_viaje: datos.hora,
        pasajeros: datos.pasajeros,
        precio_total: datos.precio,
        id_detalle_ruta: datos.id_detalle_ruta,
        email_verification_token: '',
        fingerprint_session_id: fingerprint.sessionId || '',
        finger: '',
        nombre_cliente: defaults.nombre_cliente || '',
        correo_cliente: defaults.correo_cliente || '',
        telefono_country_code: defaults.telefono_country_code || defaultCountry?.code || '',
        telefono_national: defaults.telefono_national || '',
        punto_recogida: '',
        punto_destino: '',
        notas_adicionales: '',
        cc_name: paymentDefaults.cc_name || '',
        cc_number: '',
        cc_exp_month: paymentDefaults.cc_exp_month || '',
        cc_exp_year: paymentDefaults.cc_exp_year || '',
        cc_cvv2: '',
        cc_type: paymentDefaults.cc_type || 'visa',
        billing_address: '',
        billing_city: '',
        billing_state: '',
        billing_country: paymentDefaults.billing_country || 'Guatemala',
        billing_zip: '',
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

    const normalizeEmail = (value) => String(value || '').trim().toLowerCase();
    const isAuthenticatedEmail = authenticatedEmail && normalizeEmail(data.correo_cliente) === normalizeEmail(authenticatedEmail);

    const submitReservation = (token = data.email_verification_token) => {
        transform((values) => ({
            ...values,
            email_verification_token: token,
        }));
        post(urls.store || '/reservas/confirmar', {
            preserveScroll: true,
        });
    };

    const startSubmit = async (event) => {
        event.preventDefault();

        if (isAuthenticatedEmail || data.email_verification_token) {
            submitReservation();
            return;
        }

        if (!data.correo_cliente) {
            setEmailMessage('Ingresa tu correo antes de verificar.');
            return;
        }

        setEmailModalOpen(true);
        await sendCode();
    };

    const sendCode = async () => {
        setEmailBusy(true);
        setEmailMessage('');

        try {
            const response = await axios.post(urls.sendEmailCode || '/reservas/email-code/send', {
                correo_cliente: data.correo_cliente,
            });
            setEmailMessage(response.data?.message || 'Codigo enviado correctamente.');
        } catch (error) {
            setEmailMessage(error.response?.data?.message || 'No pudimos enviar el codigo.');
        } finally {
            setEmailBusy(false);
        }
    };

    const verifyCode = async () => {
        setEmailBusy(true);
        setEmailMessage('');

        try {
            const response = await axios.post(urls.verifyEmailCode || '/reservas/email-code/verify', {
                correo_cliente: data.correo_cliente,
                codigo: emailCode,
            });
            const token = response.data?.verification_token || '';
            setData('email_verification_token', token);
            setEmailMessage(response.data?.message || 'Correo verificado correctamente.');
            setEmailModalOpen(false);
            submitReservation(token);
        } catch (error) {
            setEmailMessage(error.response?.data?.message || 'No pudimos verificar el codigo.');
        } finally {
            setEmailBusy(false);
        }
    };

    return (
        <main className="min-h-screen bg-[linear-gradient(135deg,#ffffff_0%,#f7f7f2_58%,#ecebe5_100%)] px-4 py-8 text-slate-950 sm:px-6 lg:py-12">
            <Head>
                {fingerprint.orgId && fingerprint.fullSessionId && (
                    <script src={`https://h.online-metrix.net/fp/tags.js?org_id=${fingerprint.orgId}&session_id=${fingerprint.fullSessionId}`} type="application/javascript" />
                )}
            </Head>

            <div className="mx-auto max-w-7xl">
                <Link
                    href={urls.cotizar || '/'}
                    data={{
                        origen: ruta.origen_id,
                        destino: ruta.destino_id,
                        fecha: datos.fecha,
                        hora: datos.hora,
                        pasajeros: datos.pasajeros,
                    }}
                    className="mb-8 inline-flex text-xs font-black uppercase tracking-widest text-slate-700 no-underline"
                >
                    <i className="fas fa-arrow-left mr-2" />
                    Volver a vehiculos
                </Link>

                <form onSubmit={startSubmit} className="grid gap-8 lg:grid-cols-[1fr_360px]">
                    <div className="space-y-6">
                        <div>
                            <p className="mb-3 text-xs font-black uppercase tracking-[0.2em] text-yellow-600">Finalizar reserva</p>
                            <h1 className="mb-3 text-4xl font-black">Detalles del traslado</h1>
                            <p className="mb-0 max-w-2xl text-slate-600">Revisaremos la informacion para confirmar tu traslado de forma segura.</p>
                        </div>

                        <Panel title="Informacion personal" kicker="Paso 1">
                            <div className="grid gap-4 md:grid-cols-2">
                                <TextInput label="Nombre completo" name="nombre_cliente" data={data} setData={setData} errors={errors} autoComplete="name" />
                                <TextInput label="Correo electronico" name="correo_cliente" data={data} setData={setData} errors={errors} type="email" autoComplete="email" />
                                <div className="md:col-span-2">
                                    <label className="mb-2 block text-sm font-black text-slate-700">Telefono</label>
                                    <div className="grid gap-3 sm:grid-cols-[180px_1fr]">
                                        <select
                                            value={data.telefono_country_code}
                                            onChange={(event) => setData('telefono_country_code', event.target.value)}
                                            required
                                            className="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-900 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                        >
                                            {countries.map((country) => (
                                                <option key={country.code} value={country.code}>{country.dial} {country.name}</option>
                                            ))}
                                        </select>
                                        <input
                                            type="tel"
                                            value={data.telefono_national}
                                            onChange={(event) => setData('telefono_national', event.target.value)}
                                            required
                                            autoComplete="tel-national"
                                            placeholder="Numero de telefono"
                                            className="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-900 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                        />
                                    </div>
                                    <FieldError message={errors.telefono_country_code || errors.telefono_national} />
                                </div>
                            </div>
                        </Panel>

                        <Panel title="Puntos del viaje" kicker="Paso 2">
                            <div className="grid gap-4 md:grid-cols-2">
                                <TextArea label="Punto de recogida" name="punto_recogida" data={data} setData={setData} errors={errors} placeholder="Hotel, direccion o referencia de recogida" />
                                <TextArea label="Punto de destino" name="punto_destino" data={data} setData={setData} errors={errors} placeholder="Hotel, direccion o referencia de destino" />
                                <div className="md:col-span-2">
                                    <TextArea label="Notas adicionales" name="notas_adicionales" data={data} setData={setData} errors={errors} required={false} placeholder="Equipaje, silla infantil, horario especial u otra informacion" />
                                </div>
                            </div>
                        </Panel>

                        <Panel title="Pago seguro" kicker="Paso 3">
                            <div className="grid gap-4 md:grid-cols-2">
                                <TextInput label="Nombre en la tarjeta" name="cc_name" data={data} setData={setData} errors={errors} autoComplete="cc-name" />
                                <TextInput label="Numero de tarjeta" name="cc_number" data={data} setData={setData} errors={errors} autoComplete="cc-number" placeholder="4111 1111 1111 1111" />
                                <TextInput label="Mes" name="cc_exp_month" data={data} setData={setData} errors={errors} autoComplete="cc-exp-month" placeholder="01" />
                                <TextInput label="Ano" name="cc_exp_year" data={data} setData={setData} errors={errors} autoComplete="cc-exp-year" placeholder="2026" />
                                <TextInput label="CVV" name="cc_cvv2" data={data} setData={setData} errors={errors} type="password" autoComplete="cc-csc" placeholder="123" />
                                <div>
                                    <label className="mb-2 block text-sm font-black text-slate-700">Tipo</label>
                                    <select
                                        value={data.cc_type}
                                        onChange={(event) => setData('cc_type', event.target.value)}
                                        required
                                        className="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-900 shadow-sm outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                                    >
                                        <option value="visa">Visa</option>
                                        <option value="mastercard">Mastercard</option>
                                    </select>
                                    <FieldError message={errors.cc_type} />
                                </div>
                                <TextInput label="Direccion de facturacion" name="billing_address" data={data} setData={setData} errors={errors} autoComplete="billing street-address" />
                                <TextInput label="Ciudad" name="billing_city" data={data} setData={setData} errors={errors} autoComplete="billing address-level2" />
                                <TextInput label="Departamento" name="billing_state" data={data} setData={setData} errors={errors} autoComplete="billing address-level1" />
                                <TextInput label="Pais" name="billing_country" data={data} setData={setData} errors={errors} autoComplete="billing country-name" />
                                <TextInput label="Codigo postal" name="billing_zip" data={data} setData={setData} errors={errors} autoComplete="billing postal-code" />
                            </div>
                            <FieldError message={errors.reserva} />
                            <button
                                type="submit"
                                disabled={processing}
                                className="mt-6 w-full rounded-2xl bg-slate-950 px-6 py-4 text-sm font-black uppercase tracking-widest text-white shadow-xl transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-70"
                            >
                                {processing ? 'Procesando...' : 'Confirmar y pagar'}
                            </button>
                        </Panel>
                    </div>

                    <aside className="h-fit rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm lg:sticky lg:top-6">
                        <p className="mb-2 text-xs font-black uppercase tracking-[0.2em] text-yellow-600">Resumen</p>
                        <h2 className="mb-5 text-2xl font-black">Tu traslado</h2>
                        <div className="mb-5 rounded-2xl bg-slate-50 p-4">
                            <p className="mb-2 text-sm font-black">{ruta.origen?.nombre}</p>
                            <div className="mb-2 h-px bg-slate-200" />
                            <p className="mb-0 text-sm font-black">{ruta.destino?.nombre}</p>
                        </div>
                        <div className="space-y-3">
                            {[
                                ['Vehiculo', vehiculoSeleccionado?.nombre || 'Vehiculo'],
                                ['Fecha', datos.fecha],
                                ['Hora', datos.hora],
                                ['Pasajeros', `${datos.pasajeros} pers.`],
                                ['Total', money(datos.precio)],
                            ].map(([label, value]) => (
                                <div key={label} className="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                                    <span className="text-xs font-black uppercase tracking-widest text-slate-500">{label}</span>
                                    <strong className="text-right">{value}</strong>
                                </div>
                            ))}
                        </div>
                        {!isAuthenticatedEmail && (
                            <div className="mt-5 rounded-2xl bg-yellow-50 p-4 text-sm font-bold text-yellow-900">
                                Verificaremos el correo antes de enviar el pago.
                            </div>
                        )}
                    </aside>
                </form>
            </div>

            {emailModalOpen && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 px-4">
                    <div className="w-full max-w-md rounded-[1.75rem] bg-white p-6 shadow-2xl">
                        <button type="button" onClick={() => setEmailModalOpen(false)} className="ml-auto block h-9 w-9 rounded-full bg-slate-100 text-slate-600">
                            <i className="fas fa-times" />
                        </button>
                        <div className="mb-5 text-center">
                            <div className="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-yellow-400 text-black">
                                <i className="fas fa-envelope-open-text" />
                            </div>
                            <p className="mb-2 text-xs font-black uppercase tracking-[0.2em] text-yellow-600">Verificacion</p>
                            <h3 className="text-2xl font-black">Confirma tu correo</h3>
                            <p className="text-sm text-slate-600">Enviamos un codigo a <strong>{data.correo_cliente}</strong>.</p>
                        </div>
                        <input
                            type="text"
                            value={emailCode}
                            onChange={(event) => setEmailCode(event.target.value)}
                            maxLength="4"
                            inputMode="numeric"
                            placeholder="0000"
                            className="w-full rounded-2xl border border-slate-200 px-4 py-3 text-center text-2xl font-black tracking-[0.4em] outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-100"
                        />
                        {emailMessage && <p className="mt-3 rounded-2xl bg-slate-50 p-3 text-center text-sm font-bold text-slate-700">{emailMessage}</p>}
                        <button
                            type="button"
                            onClick={verifyCode}
                            disabled={emailBusy || emailCode.length !== 4}
                            className="mt-4 w-full rounded-2xl bg-slate-950 px-5 py-3 text-sm font-black uppercase tracking-widest text-white disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            {emailBusy ? 'Verificando...' : 'Verificar y pagar'}
                        </button>
                        <button type="button" onClick={sendCode} disabled={emailBusy} className="mt-3 w-full text-sm font-black text-slate-600">
                            Reenviar codigo
                        </button>
                        <Link href={urls.login || '/login'} className="mt-3 block text-center text-sm font-black text-blue-700 no-underline">
                            Iniciar sesion
                        </Link>
                    </div>
                </div>
            )}
        </main>
    );
}

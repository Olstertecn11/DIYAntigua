import { Head, Link, router } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useState } from 'react';
import PublicLayout from '@/Layouts/PublicLayout';

export default function Security({ user, urls }) {
    const [step, setStep] = useState(1);
    const [currentPassword, setCurrentPassword] = useState('');
    const [code, setCode] = useState('');
    const [token, setToken] = useState('');
    const [password, setPassword] = useState('');
    const [confirmation, setConfirmation] = useState('');
    const [seconds, setSeconds] = useState(0);
    const [busy, setBusy] = useState(false);
    const [message, setMessage] = useState('');
    const [error, setError] = useState('');

    useEffect(() => {
        if (seconds <= 0) return undefined;
        const timer = window.setInterval(() => setSeconds((value) => Math.max(0, value - 1)), 1000);
        return () => window.clearInterval(timer);
    }, [seconds]);

    const sendCode = async (event) => {
        event?.preventDefault();
        setBusy(true);
        setError('');
        setMessage('');

        try {
            const response = await axios.post(urls.send, { current_password: currentPassword });
            setMessage(response.data.message);
            setSeconds(response.data.expires_in || 600);
            setStep(2);
        } catch (requestError) {
            setError(requestError.response?.data?.message || Object.values(requestError.response?.data?.errors || {})[0]?.[0] || 'No pudimos enviar el código.');
        } finally {
            setBusy(false);
        }
    };

    const verifyCode = async (event) => {
        event.preventDefault();
        setBusy(true);
        setError('');

        try {
            const response = await axios.post(urls.verify, { code });
            setToken(response.data.verification_token);
            setMessage(response.data.message);
            setStep(3);
        } catch (requestError) {
            setError(requestError.response?.data?.message || Object.values(requestError.response?.data?.errors || {})[0]?.[0] || 'No pudimos verificar el código.');
        } finally {
            setBusy(false);
        }
    };

    const updatePassword = (event) => {
        event.preventDefault();
        setError('');
        router.put(urls.update, {
            verification_token: token,
            password,
            password_confirmation: confirmation,
        }, {
            onError: (errors) => setError(errors.password || errors.verification_token || 'Revisa los datos ingresados.'),
        });
    };

    return (
        <PublicLayout>
            <Head title="Seguridad de cuenta" />
            <main className="relative min-h-screen overflow-hidden bg-[#f5f4ef] pb-16 pt-28 text-black">
                <div className="pointer-events-none absolute -left-28 top-24 h-80 w-80 rounded-full bg-[#FCCA00]/20 blur-3xl" />
                <div className="pointer-events-none absolute -right-24 bottom-20 h-96 w-96 rounded-full bg-black/10 blur-3xl" />

                <div className="relative mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <Link href={urls.profile} className="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white/70 px-4 py-2 text-xs font-black uppercase tracking-widest text-black no-underline backdrop-blur-xl transition hover:border-[#FCCA00]">
                        <i className="fas fa-arrow-left" /> Volver al perfil
                    </Link>

                    <div className="mt-6 grid overflow-hidden rounded-[2.25rem] border border-black/10 bg-white/70 shadow-[0_35px_100px_rgba(0,0,0,.14)] backdrop-blur-2xl lg:grid-cols-[0.82fr_1.18fr]">
                        <aside className="relative overflow-hidden bg-[#0b0b09] p-7 text-white sm:p-10">
                            <div className="absolute -right-24 -top-24 h-64 w-64 rounded-full bg-[#FCCA00]/20 blur-3xl" />
                            <div className="relative">
                                <span className="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FCCA00] text-xl text-black shadow-[0_18px_48px_rgba(252,202,0,.32)]"><i className="fas fa-shield-halved" /></span>
                                <p className="mt-8 text-[10px] font-black uppercase tracking-[0.24em] text-[#FCCA00]">Centro de seguridad</p>
                                <h1 className="mt-3 text-4xl font-black leading-tight">Cambia tu contraseña con verificación.</h1>
                                <p className="mt-4 text-sm leading-7 text-white/55">Confirmaremos que eres tú mediante tu contraseña actual y un código enviado a <strong className="text-white">{user.email}</strong>.</p>
                                <div className="mt-9 grid gap-3">
                                    <Step number="1" title="Confirma tu identidad" active={step === 1} done={step > 1} />
                                    <Step number="2" title="Verifica el código" active={step === 2} done={step > 2} />
                                    <Step number="3" title="Crea tu contraseña" active={step === 3} />
                                </div>
                            </div>
                        </aside>

                        <section className="flex min-h-[560px] items-center p-7 sm:p-12">
                            <div className="mx-auto w-full max-w-lg">
                                <p className="text-[10px] font-black uppercase tracking-[0.22em] text-[#8a6b00]">Paso {step} de 3</p>
                                {step === 1 && (
                                    <form onSubmit={sendCode} className="mt-3">
                                        <h2 className="text-3xl font-black">Primero, confirma que eres tú</h2>
                                        <p className="mt-3 text-sm leading-6 text-black/55">Ingresa la contraseña que utilizas actualmente. Después enviaremos el código.</p>
                                        <SecureInput label="Contraseña actual" value={currentPassword} onChange={setCurrentPassword} autoComplete="current-password" />
                                        <PrimaryButton busy={busy}>Enviar código de verificación</PrimaryButton>
                                    </form>
                                )}

                                {step === 2 && (
                                    <form onSubmit={verifyCode} className="mt-3">
                                        <h2 className="text-3xl font-black">Revisa tu correo</h2>
                                        <p className="mt-3 text-sm leading-6 text-black/55">Escribe el código de 6 dígitos. Vence en <strong>{formatTime(seconds)}</strong>.</p>
                                        <input
                                            value={code}
                                            onChange={(event) => setCode(event.target.value.replace(/\D/g, '').slice(0, 6))}
                                            inputMode="numeric"
                                            autoComplete="one-time-code"
                                            autoFocus
                                            className="mt-7 w-full rounded-3xl border border-black/10 bg-white px-5 py-5 text-center text-3xl font-black tracking-[0.45em] outline-none transition focus:border-[#FCCA00] focus:ring-4 focus:ring-[#FCCA00]/20"
                                            placeholder="000000"
                                        />
                                        <PrimaryButton busy={busy}>Verificar código</PrimaryButton>
                                        <button type="button" disabled={busy || seconds > 540} onClick={sendCode} className="mt-4 w-full text-center text-xs font-black text-black/55 disabled:opacity-35">Reenviar código</button>
                                    </form>
                                )}

                                {step === 3 && (
                                    <form onSubmit={updatePassword} className="mt-3">
                                        <h2 className="text-3xl font-black">Crea una nueva contraseña</h2>
                                        <p className="mt-3 text-sm leading-6 text-black/55">Usa al menos 8 caracteres e incluye letras y números.</p>
                                        <SecureInput label="Nueva contraseña" value={password} onChange={setPassword} autoComplete="new-password" />
                                        <SecureInput label="Confirmar contraseña" value={confirmation} onChange={setConfirmation} autoComplete="new-password" />
                                        <PrimaryButton>Guardar nueva contraseña</PrimaryButton>
                                    </form>
                                )}

                                {(message || error) && <div className={`mt-5 rounded-2xl border px-4 py-3 text-sm font-bold ${error ? 'border-red-200 bg-red-50 text-red-700' : 'border-green-200 bg-green-50 text-green-800'}`}>{error || message}</div>}
                            </div>
                        </section>
                    </div>
                </div>
            </main>
        </PublicLayout>
    );
}

function Step({ number, title, active, done }) {
    return (
        <div className={`flex items-center gap-3 rounded-2xl border px-4 py-3 transition ${active ? 'border-[#FCCA00]/50 bg-[#FCCA00]/10' : 'border-white/10 bg-white/[0.04]'}`}>
            <span className={`flex h-9 w-9 items-center justify-center rounded-xl text-xs font-black ${done || active ? 'bg-[#FCCA00] text-black' : 'bg-white/10 text-white/45'}`}>{done ? <i className="fas fa-check" /> : number}</span>
            <strong className={active || done ? 'text-white' : 'text-white/40'}>{title}</strong>
        </div>
    );
}

function SecureInput({ label, value, onChange, autoComplete }) {
    const [visible, setVisible] = useState(false);
    return (
        <label className="mt-6 block">
            <span className="text-[11px] font-black uppercase tracking-[0.16em] text-black/60">{label}</span>
            <span className="mt-2 flex overflow-hidden rounded-2xl border border-black/10 bg-white focus-within:border-[#FCCA00] focus-within:ring-4 focus-within:ring-[#FCCA00]/20">
                <input type={visible ? 'text' : 'password'} value={value} onChange={(event) => onChange(event.target.value)} autoComplete={autoComplete} required className="min-w-0 flex-1 bg-transparent px-4 py-4 text-sm font-bold outline-none" />
                <button type="button" onClick={() => setVisible((state) => !state)} className="w-14 text-black/45"><i className={`fas ${visible ? 'fa-eye-slash' : 'fa-eye'}`} /></button>
            </span>
        </label>
    );
}

function PrimaryButton({ children, busy = false }) {
    return <button disabled={busy} className="mt-7 inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#FCCA00] px-6 py-4 text-sm font-black text-black shadow-[0_16px_38px_rgba(252,202,0,.3)] transition hover:-translate-y-0.5 hover:bg-yellow-300 disabled:opacity-60">{busy && <i className="fas fa-circle-notch animate-spin" />}{children}</button>;
}

function formatTime(seconds) {
    return `${String(Math.floor(seconds / 60)).padStart(2, '0')}:${String(seconds % 60).padStart(2, '0')}`;
}

import { useState } from 'react';

export default function FloatingActions() {
    const [language, setLanguage] = useState('ES');

    const toggleLanguage = () => {
        setLanguage((current) => (current === 'ES' ? 'EN' : 'ES'));
    };

    return (
        <div className="fixed bottom-5 right-5 z-[120] flex flex-col gap-3">
            <button
                type="button"
                onClick={toggleLanguage}
                className="flex h-12 w-12 items-center justify-center rounded-full border border-black/10 bg-white text-xs font-black text-slate-950 shadow-2xl transition hover:-translate-y-0.5"
                title="Cambiar idioma"
                aria-label="Cambiar idioma"
            >
                {language}
            </button>
            <a
                href="https://wa.me/50200000000"
                target="_blank"
                rel="noreferrer"
                className="flex h-12 w-12 items-center justify-center rounded-full bg-green-500 text-xl text-white no-underline shadow-2xl transition hover:-translate-y-0.5 hover:bg-green-600"
                title="WhatsApp"
                aria-label="WhatsApp"
            >
                <i className="fab fa-whatsapp" />
            </a>
        </div>
    );
}

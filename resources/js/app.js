import './bootstrap';
import { createInertiaApp } from '@inertiajs/react';
import React from 'react';
import { createRoot } from 'react-dom/client';
import { LanguageProvider } from '@/Contexts/LanguageContext';

createInertiaApp({
    progress: {
        color: '#FCCA00',
        showSpinner: false,
    },
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });

        return pages[`./Pages/${name}.jsx`];
    },
    setup({ el, App, props }) {
        createRoot(el).render(
            React.createElement(LanguageProvider, null, React.createElement(App, props)),
        );
    },
    title: (title) => (title ? `${title} - DYANTIGUA` : 'DYANTIGUA'),
});

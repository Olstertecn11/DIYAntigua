<div class="fixed bottom-24 right-5 z-[9998]">
    <div class="relative">
        <button id="language-toggle" type="button"
            class="h-12 px-4 rounded-full bg-slate-950 text-white border border-white/10 shadow-2xl flex items-center gap-2 font-black text-sm hover:border-yellow-300/50 transition">
            <i class="fa-solid fa-language text-yellow-300"></i>
            <span id="language-current-label">ES</span>
        </button>

        <div id="language-panel"
            class="hidden absolute bottom-14 right-0 w-48 rounded-3xl bg-slate-950 border border-white/10 shadow-2xl p-3">

            <p class="text-white font-black text-sm mb-3">
                Idioma
            </p>

            <div class="grid grid-cols-2 gap-2">
                <button type="button" data-lang-option="es"
                    class="language-option rounded-2xl px-4 py-3 text-sm font-black bg-yellow-300 text-slate-950">
                    ES
                </button>

                <button type="button" data-lang-option="en"
                    class="language-option rounded-2xl px-4 py-3 text-sm font-black bg-white/10 text-white hover:bg-white/15 transition">
                    EN
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.AppTranslations = {
        es: {
            reserve_now: 'Reservar ahora',
            book_transfer: 'Reservar Traslado',
            destinations: 'Destinos',
            information: 'Información',
            login: 'Iniciar Sesión',
            register: 'Registrarse',
            quick_booking: 'Reserva rápida',
            quote_transfer: 'Cotiza tu traslado',
            select_route_date: 'Selecciona origen, destino, fecha y número de pasajeros.',
            origin: 'Origen',
            destination: 'Destino',
            date: 'Fecha',
            time: 'Hora',
            passengers: 'Pasajeros',
            view_rates: 'Ver tarifas disponibles',
            why_choose_us: 'Por qué elegirnos',
            experience_title: 'Una experiencia diseñada para viajar tranquilo.',
            footer_navigation: 'Navegación',
            footer_services: 'Servicios',
            footer_contact: 'Contacto',
            footer_help: 'Ayuda',
            footer_support: 'Soporte',
            footer_terms: 'Términos',
            footer_privacy: 'Privacidad',
        },

        en: {
            reserve_now: 'Book now',
            book_transfer: 'Book Transfer',
            destinations: 'Destinations',
            information: 'Information',
            login: 'Sign In',
            register: 'Register',
            quick_booking: 'Quick booking',
            quote_transfer: 'Quote your transfer',
            select_route_date: 'Select origin, destination, date, and number of passengers.',
            origin: 'Origin',
            destination: 'Destination',
            date: 'Date',
            time: 'Time',
            passengers: 'Passengers',
            view_rates: 'View available rates',
            why_choose_us: 'Why choose us',
            experience_title: 'An experience designed for stress-free travel.',
            footer_navigation: 'Navigation',
            footer_services: 'Services',
            footer_contact: 'Contact',
            footer_help: 'Help',
            footer_support: 'Support',
            footer_terms: 'Terms',
            footer_privacy: 'Privacy',
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('language-toggle');
        const panel = document.getElementById('language-panel');
        const currentLabel = document.getElementById('language-current-label');
        const optionButtons = document.querySelectorAll('[data-lang-option]');

        const supportedLanguages = ['es', 'en'];

        function getInitialLanguage() {
            const savedLanguage = localStorage.getItem('site_language');

            if (savedLanguage && supportedLanguages.includes(savedLanguage)) {
                return savedLanguage;
            }

            const browserLanguage = navigator.language || navigator.userLanguage || 'es';

            if (browserLanguage.toLowerCase().startsWith('en')) {
                return 'en';
            }

            return 'es';
        }

        function applyLanguage(language) {
            const translations = window.AppTranslations[language] || window.AppTranslations.es;

            document.documentElement.lang = language;
            localStorage.setItem('site_language', language);

            if (currentLabel) {
                currentLabel.textContent = language.toUpperCase();
            }

            document.querySelectorAll('[data-i18n]').forEach((element) => {
                const key = element.getAttribute('data-i18n');

                if (translations[key]) {
                    element.textContent = translations[key];
                }
            });

            document.querySelectorAll('[data-i18n-placeholder]').forEach((element) => {
                const key = element.getAttribute('data-i18n-placeholder');

                if (translations[key]) {
                    element.setAttribute('placeholder', translations[key]);
                }
            });

            optionButtons.forEach((button) => {
                const buttonLanguage = button.getAttribute('data-lang-option');

                if (buttonLanguage === language) {
                    button.classList.remove('bg-white/10', 'text-white', 'hover:bg-white/15');
                    button.classList.add('bg-yellow-300', 'text-slate-950');
                } else {
                    button.classList.remove('bg-yellow-300', 'text-slate-950');
                    button.classList.add('bg-white/10', 'text-white', 'hover:bg-white/15');
                }
            });
        }

        if (toggleButton && panel) {
            toggleButton.addEventListener('click', function() {
                panel.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                if (!toggleButton.contains(event.target) && !panel.contains(event.target)) {
                    panel.classList.add('hidden');
                }
            });
        }

        optionButtons.forEach((button) => {
            button.addEventListener('click', function() {
                const language = this.getAttribute('data-lang-option');
                applyLanguage(language);

                if (panel) {
                    panel.classList.add('hidden');
                }
            });
        });

        applyLanguage(getInitialLanguage());
    });
</script>

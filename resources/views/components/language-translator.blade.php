<div class="notranslate fixed bottom-24 right-5 z-[9998]" translate="no">
    <div class="relative">
        <button id="language-toggle" type="button"
            class="h-12 px-4 rounded-full bg-slate-950 text-white border border-white/10 shadow-2xl flex items-center gap-2 font-black text-sm hover:border-yellow-300/50 transition">
            <i class="fa-solid fa-language text-yellow-300"></i>
            <span id="language-label">ES</span>
        </button>

        <div id="language-panel"
            class="hidden absolute bottom-14 right-0 w-48 rounded-3xl bg-slate-950 border border-white/10 shadow-2xl p-3">
            <p class="text-white font-black text-sm mb-3">
                Idioma
            </p>

            <div class="grid grid-cols-2 gap-2">
                <button type="button" data-translate-lang="es"
                    class="lang-btn rounded-2xl px-4 py-3 text-sm font-black bg-yellow-300 text-slate-950">
                    ES
                </button>

                <button type="button" data-translate-lang="en"
                    class="lang-btn rounded-2xl px-4 py-3 text-sm font-black bg-white/10 text-white hover:bg-white/15 transition">
                    EN
                </button>
            </div>
        </div>
    </div>
</div>

<div id="google_translate_element" style="position:absolute; left:-9999px; top:-9999px;"></div>

<style>
    body {
        top: 0 !important;
    }

    .goog-te-banner-frame,
    iframe.goog-te-banner-frame,
    body>.skiptranslate {
        display: none !important;
    }

    .goog-te-gadget {
        font-size: 0 !important;
    }

    .goog-logo-link,
    .goog-te-gadget span {
        display: none !important;
    }
</style>

<script>
    window.googleTranslateElementInit = function() {
        new google.translate.TranslateElement({
            pageLanguage: 'es',
            includedLanguages: 'es,en',
            autoDisplay: false
        }, 'google_translate_element');
    };

    document.addEventListener('DOMContentLoaded', function() {
        const toggle = document.getElementById('language-toggle');
        const panel = document.getElementById('language-panel');
        const label = document.getElementById('language-label');
        const buttons = document.querySelectorAll('[data-translate-lang]');

        function setTranslateCookie(lang) {
            const value = lang === 'es' ? '/es/es' : '/es/' + lang;

            document.cookie = 'googtrans=' + value + ';path=/';
            document.cookie = 'googtrans=' + value + ';path=/;domain=' + window.location.hostname;

            localStorage.setItem('site_language', lang);
        }

        function updateUI(lang) {
            if (label) {
                label.textContent = lang.toUpperCase();
            }

            buttons.forEach(button => {
                const buttonLang = button.getAttribute('data-translate-lang');

                if (buttonLang === lang) {
                    button.classList.remove('bg-white/10', 'text-white', 'hover:bg-white/15');
                    button.classList.add('bg-yellow-300', 'text-slate-950');
                } else {
                    button.classList.remove('bg-yellow-300', 'text-slate-950');
                    button.classList.add('bg-white/10', 'text-white', 'hover:bg-white/15');
                }
            });
        }

        function changeLanguage(lang) {
            setTranslateCookie(lang);
            updateUI(lang);

            setTimeout(() => {
                window.location.reload();
            }, 250);
        }

        function getSavedLanguage() {
            const saved = localStorage.getItem('site_language');

            if (saved === 'en' || saved === 'es') {
                return saved;
            }

            const cookie = document.cookie.match(/googtrans=\/es\/([a-z]+)/);

            if (cookie && cookie[1]) {
                return cookie[1];
            }

            return 'es';
        }

        if (toggle && panel) {
            toggle.addEventListener('click', function() {
                panel.classList.toggle('hidden');
            });

            document.addEventListener('click', function(event) {
                if (!toggle.contains(event.target) && !panel.contains(event.target)) {
                    panel.classList.add('hidden');
                }
            });
        }

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const lang = this.getAttribute('data-translate-lang');
                changeLanguage(lang);
            });
        });

        updateUI(getSavedLanguage());
    });
</script>

<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

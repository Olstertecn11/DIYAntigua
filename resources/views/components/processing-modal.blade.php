<div id="processing-modal" class="processing-modal is-hidden" aria-live="polite" aria-modal="true" role="dialog">
    <div class="processing-modal-card">
        <div class="processing-loader" aria-hidden="true"></div>
        <p class="processing-kicker" id="processing-kicker">Procesando</p>
        <h2 id="processing-title">Un momento, por favor</h2>
        <p id="processing-message">Estamos procesando tu solicitud de forma segura.</p>
    </div>
</div>

<style>
    .processing-modal {
        position: fixed;
        inset: 0;
        z-index: 1000000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: rgba(0, 0, 0, .78);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }

    .processing-modal.is-hidden {
        display: none;
    }

    .processing-modal-card {
        width: min(430px, 100%);
        border-radius: 26px;
        border: 1px solid rgba(255, 255, 255, .12);
        background:
            radial-gradient(circle at 18% 0%, rgba(252, 202, 0, .18), transparent 34%),
            #080808;
        color: #ffffff;
        padding: 30px;
        text-align: center;
        box-shadow: 0 34px 100px rgba(0, 0, 0, .44);
    }

    .processing-loader {
        width: 64px;
        height: 64px;
        margin: 0 auto 20px;
        border-radius: 999px;
        border: 5px solid rgba(255, 255, 255, .12);
        border-top-color: #FCCA00;
        animation: processingSpin .85s linear infinite;
    }

    .processing-kicker {
        color: #FCCA00;
        font-size: 11px;
        font-weight: 950;
        letter-spacing: .16em;
        margin: 0 0 8px;
        text-transform: uppercase;
    }

    .processing-modal-card h2 {
        color: #ffffff;
        font-size: 24px;
        font-weight: 950;
        margin: 0 0 10px;
    }

    .processing-modal-card p:last-child {
        color: rgba(255, 255, 255, .70);
        font-size: 14px;
        font-weight: 700;
        line-height: 1.55;
        margin: 0;
    }

    @keyframes processingSpin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    (function () {
        const state = {
            visible: false,
            lockedForms: new WeakSet(),
        };

        function elements() {
            return {
                modal: document.getElementById('processing-modal'),
                kicker: document.getElementById('processing-kicker'),
                title: document.getElementById('processing-title'),
                message: document.getElementById('processing-message'),
            };
        }

        function show(options = {}) {
            const ui = elements();

            if (!ui.modal) {
                return;
            }

            ui.kicker.textContent = options.kicker || 'Procesando';
            ui.title.textContent = options.title || 'Un momento, por favor';
            ui.message.textContent = options.message || 'Estamos procesando tu solicitud de forma segura.';
            ui.modal.classList.remove('is-hidden');
            state.visible = true;
        }

        function hide() {
            const ui = elements();

            if (!ui.modal) {
                return;
            }

            ui.modal.classList.add('is-hidden');
            state.visible = false;
        }

        function offline(message) {
            show({
                kicker: 'Sin conexión',
                title: 'Revisa tu internet',
                message: message || 'No pudimos continuar porque el navegador está sin conexión.',
            });

            setTimeout(hide, 2600);
        }

        async function wrap(promise, options = {}) {
            show(options);

            try {
                return await promise;
            } finally {
                if (options.hide !== false) {
                    hide();
                }
            }
        }

        window.DIYProcessing = {
            show,
            hide,
            offline,
            wrap,
            isVisible: () => state.visible,
        };

        document.addEventListener('submit', function (event) {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || form.dataset.processing === 'off') {
                return;
            }

            const method = (form.getAttribute('method') || 'GET').toUpperCase();
            const isCritical = method !== 'GET' || form.dataset.processing === 'on';

            if (!isCritical) {
                return;
            }

            if (!navigator.onLine) {
                event.preventDefault();
                offline('Tu conexión parece estar caída. No se envió la solicitud.');
                return;
            }

            if (state.lockedForms.has(form)) {
                event.preventDefault();
                return;
            }

            if (!form.checkValidity()) {
                return;
            }

            state.lockedForms.add(form);

            form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach((button) => {
                button.disabled = true;
                button.setAttribute('aria-disabled', 'true');
            });

            show({
                kicker: form.dataset.processingKicker || 'Solicitud segura',
                title: form.dataset.processingTitle || 'Procesando solicitud',
                message: form.dataset.processingMessage || 'No cierres esta ventana mientras terminamos.',
            });
        }, true);

        window.addEventListener('offline', function () {
            if (state.visible) {
                offline('Se perdió la conexión. Si estabas pagando, espera el resultado antes de intentar de nuevo.');
            }
        });
    })();
</script>

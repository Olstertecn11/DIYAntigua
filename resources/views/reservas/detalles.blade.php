@extends('layouts.app')

@section('content')
    <script src="https://h.online-metrix.net/fp/tags.js?org_id={{ $fingerprintOrgId }}&session_id={{ $fingerprintFullSessionId }}" type="application/javascript"></script>
    <noscript>
        <iframe style="width:100px;height:100px;border:0;position:absolute;top:-5000px;" src="https://h.online-metrix.net/fp/tags?org_id={{ $fingerprintOrgId }}&session_id={{ $fingerprintFullSessionId }}"></iframe>
    </noscript>

    <div class="reservation-details-page min-h-screen py-5">
        <div class="container max-w-6xl position-relative">
            <form action="{{ route('reservas.store') }}" method="POST" id="reservation-form">
                @csrf

                <input type="hidden" name="ruta_id" value="{{ $ruta->id }}">
                <input type="hidden" name="vehiculo_id" value="{{ $datos['vehiculo_id'] }}">
                <input type="hidden" name="fecha_viaje" value="{{ $datos['fecha'] }}">
                <input type="hidden" name="hora_viaje" value="{{ $datos['hora'] }}">
                <input type="hidden" name="pasajeros" value="{{ $datos['pasajeros'] }}">
                <input type="hidden" name="precio_total" value="{{ $datos['precio'] }}">
                <input type="hidden" name="id_detalle_ruta" value="{{ $datos['id_detalle_ruta'] }}">
                <input type="hidden" name="email_verification_token" id="email_verification_token">
                <input type="hidden" name="fingerprint_session_id" value="{{ $fingerprintSessionId }}">
                <input type="hidden" name="finger" id="finger">

                @php
                    $vehiculoSeleccionado = $ruta->vehiculosDisponibles->where('id', $datos['vehiculo_id'])->first();
                    $paymentFields = ['cc_name', 'cc_number', 'cc_exp_month', 'cc_exp_year', 'cc_cvv2', 'cc_type', 'billing_address', 'billing_city', 'billing_state', 'billing_country', 'billing_zip', 'reserva'];
                    $showPaymentStep = collect($paymentFields)->contains(fn ($field) => $errors->has($field));
                @endphp

                <div class="row mb-5">
                    <div class="col-lg-8">
                        <span class="eyebrow-badge mb-3">
                            <i class="fas fa-clipboard-check me-2"></i>
                            Último paso
                        </span>

                        <h1 class="display-5 fw-black mb-3 text-slate-main">
                            Completa los detalles de tu reserva
                        </h1>

                        <p class="lead text-muted-custom mb-0">
                            Ingresa los datos del pasajero y los puntos exactos de recogida y destino.
                            Revisaremos la información para confirmar tu traslado de forma segura.
                        </p>
                    </div>
                </div>

                <div class="reservation-stepper mb-4" data-current-step="{{ $showPaymentStep ? 'payment' : 'details' }}">
                    <div class="stepper-item stepper-details active">
                        <span>1</span>
                        <strong>Datos del viaje</strong>
                    </div>
                    <div class="stepper-line"></div>
                    <div class="stepper-item stepper-payment {{ $showPaymentStep ? 'active' : '' }}">
                        <span>2</span>
                        <strong>Pago seguro</strong>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div id="details-step" class="reservation-step-panel {{ $showPaymentStep ? 'd-none' : '' }}">
                        <section class="form-section-card mb-4">
                            <div class="section-heading mb-4">
                                <div class="section-icon icon-gold">
                                    <i class="fas fa-user"></i>
                                </div>

                                <div>
                                    <span class="section-kicker">
                                        Información personal
                                    </span>

                                    <h2 class="h3 fw-black mb-0 text-slate-main">
                                        Detalles del pasajero
                                    </h2>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label-custom">
                                        Nombre completo
                                    </label>

                                    <div class="input-shell">
                                        <i class="fas fa-user input-icon"></i>

                                        <input type="text" name="nombre_cliente" required
                                            class="form-control modern-input" placeholder="Ej. Juan Pérez"
                                            value="{{ old('nombre_cliente', auth()->user()?->name) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">
                                        Correo electrónico
                                    </label>

                                    <div class="input-shell">
                                        <i class="fas fa-envelope input-icon"></i>

                                        <input type="email" name="correo_cliente" required
                                            class="form-control modern-input" placeholder="usuario@gmail.com"
                                            value="{{ old('correo_cliente', auth()->user()?->email) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">
                                        Teléfono / WhatsApp
                                    </label>

                                    <div class="input-shell">
                                        <i class="fab fa-whatsapp input-icon"></i>

                                        <input type="tel" name="telefono_cliente" required
                                            class="form-control modern-input" placeholder="+502 0000-0000"
                                            value="{{ old('telefono_cliente') }}">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="form-section-card mb-4">
                            <div class="section-heading mb-4">
                                <div class="section-icon icon-blue">
                                    <i class="fas fa-map-location-dot"></i>
                                </div>

                                <div>
                                    <span class="section-kicker">
                                        Ruta y ubicación
                                    </span>

                                    <h2 class="h3 fw-black mb-0 text-slate-main">
                                        Puntos de traslado
                                    </h2>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label-custom label-pickup">
                                        Punto de recogida
                                        <span>Dirección exacta</span>
                                    </label>

                                    <div class="textarea-shell pickup-shell">
                                        <i class="fas fa-location-dot textarea-icon"></i>

                                        <textarea name="punto_recogida" required class="form-control modern-textarea" rows="3"
                                            placeholder="Ej. Puerta 2, salida de vuelos internacionales...">{{ old('punto_recogida') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label-custom label-destination">
                                        Destino final
                                        <span>Hotel o dirección</span>
                                    </label>

                                    <div class="textarea-shell destination-shell">
                                        <i class="fas fa-flag-checkered textarea-icon"></i>

                                        <textarea name="punto_destino" required class="form-control modern-textarea" rows="3"
                                            placeholder="Ej. Hotel Casa Santo Domingo, Antigua Guatemala...">{{ old('punto_destino') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label-custom">
                                        Notas adicionales
                                        <span>Opcional</span>
                                    </label>

                                    <div class="textarea-shell notes-shell">
                                        <i class="fas fa-pen textarea-icon"></i>

                                        <textarea name="notas_adicionales" class="form-control modern-textarea" rows="3"
                                            placeholder="Número de vuelo, silla de bebé, maletas extra, instrucciones especiales...">{{ old('notas_adicionales') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="step-actions">
                            <button type="button" id="continue-to-payment" class="confirm-btn">
                                Continuar al pago
                                <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                        </div>

                        <section id="payment-step" class="form-section-card payment-premium-card reservation-step-panel {{ $showPaymentStep ? '' : 'd-none' }}">
                            <div class="section-heading mb-4">
                                <div class="section-icon icon-gold">
                                    <i class="fas fa-credit-card"></i>
                                </div>

                                <div>
                                    <span class="section-kicker">
                                        Pago seguro
                                    </span>

                                    <h2 class="h3 fw-black mb-0 text-slate-main">
                                        Confirma y paga tu traslado
                                    </h2>
                                </div>
                            </div>

                            <div class="payment-trust-strip mb-4">
                                <div>
                                    <i class="fas fa-lock"></i>
                                    Datos protegidos
                                </div>
                                <div>
                                    <i class="fas fa-receipt"></i>
                                    Constancia digital
                                </div>
                                <div>
                                    <i class="fas fa-plane-departure"></i>
                                    Reserva al instante
                                </div>
                            </div>

                            <div class="payment-step-note mb-4">
                                <i class="fas fa-circle-check"></i>
                                Revisa tu resumen a la derecha y completa el pago para emitir tu constancia digital.
                            </div>

                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label class="form-label-custom">Nombre en la tarjeta</label>
                                    <div class="input-shell">
                                        <i class="fas fa-id-card input-icon"></i>
                                        <input type="text" name="cc_name" required class="form-control modern-input"
                                            placeholder="Como aparece en la tarjeta" value="{{ old('cc_name') }}" autocomplete="cc-name">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label-custom">Número de tarjeta</label>
                                    <div class="input-shell">
                                        <i class="fas fa-credit-card input-icon"></i>
                                        <input type="text" name="cc_number" required class="form-control modern-input"
                                            placeholder="4111 1111 1111 1111" maxlength="23" inputmode="numeric" autocomplete="cc-number">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label-custom">Mes</label>
                                    <div class="input-shell">
                                        <input type="text" name="cc_exp_month" required class="form-control modern-input ps-3"
                                            placeholder="01" maxlength="2" inputmode="numeric" value="{{ old('cc_exp_month') }}" autocomplete="cc-exp-month">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label-custom">Año</label>
                                    <div class="input-shell">
                                        <input type="text" name="cc_exp_year" required class="form-control modern-input ps-3"
                                            placeholder="2026" maxlength="4" inputmode="numeric" value="{{ old('cc_exp_year') }}" autocomplete="cc-exp-year">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label-custom">CVV</label>
                                    <div class="input-shell">
                                        <input type="password" name="cc_cvv2" required class="form-control modern-input ps-3"
                                            placeholder="4567" maxlength="4" inputmode="numeric" autocomplete="cc-csc">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label-custom">Tipo</label>
                                    <div class="input-shell">
                                        <select name="cc_type" required class="form-control modern-input ps-3">
                                            <option value="visa" @selected(old('cc_type') === 'visa')>Visa</option>
                                            <option value="mastercard" @selected(old('cc_type') === 'mastercard')>Mastercard</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label-custom">Dirección de facturación</label>
                                    <div class="input-shell">
                                        <i class="fas fa-location-dot input-icon"></i>
                                        <input type="text" name="billing_address" required class="form-control modern-input"
                                            value="{{ old('billing_address', 'Guatemala') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">Ciudad</label>
                                    <input type="text" name="billing_city" required class="form-control modern-input"
                                        value="{{ old('billing_city', 'Guatemala') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">Departamento</label>
                                    <input type="text" name="billing_state" required class="form-control modern-input"
                                        value="{{ old('billing_state', 'Guatemala') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">País</label>
                                    <input type="text" name="billing_country" required class="form-control modern-input"
                                        value="{{ old('billing_country', 'Guatemala') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">Código postal</label>
                                    <input type="text" name="billing_zip" required class="form-control modern-input"
                                        value="{{ old('billing_zip', '01001') }}">
                                </div>
                            </div>

                            <div class="step-actions justify-content-between mt-4">
                                <button type="button" id="back-to-details" class="secondary-step-btn">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Volver a datos
                                </button>

                                <button type="button" id="payment-submit-button" class="confirm-btn">
                                    Confirmar y pagar
                                    <i class="fas fa-lock ms-2"></i>
                                </button>
                            </div>
                        </section>
                    </div>

                    <div class="col-lg-4">
                        <aside class="confirmation-card sticky-lg-top">
                            <div class="confirmation-header mb-4">
                                <div>
                                    <span class="section-kicker">
                                        Confirmación
                                    </span>

                                    <h5 class="fw-black mb-0 text-white">
                                        Resumen final
                                    </h5>
                                </div>

                                <div class="confirm-icon">
                                    <i class="fas fa-shield-halved"></i>
                                </div>
                            </div>

                            <div class="selected-vehicle-box mb-4">
                                <p class="summary-label mb-1">
                                    Vehículo seleccionado
                                </p>

                                <h3 class="selected-vehicle-name mb-0">
                                    {{ Str::upper($vehiculoSeleccionado->nombre ?? 'Vehículo') }}
                                </h3>

                                <div class="selected-vehicle-meta mt-2">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $datos['pasajeros'] }} pasajero(s)
                                </div>
                            </div>

                            <div class="summary-item">
                                <div class="summary-icon-small icon-route">
                                    <i class="fas fa-route"></i>
                                </div>

                                <div>
                                    <p class="summary-label mb-1">
                                        Ruta seleccionada
                                    </p>

                                    <p class="summary-text mb-0">
                                        {{ $ruta->origen->nombre }}
                                        <i class="fas fa-arrow-right mx-1 text-warning"></i>
                                        {{ $ruta->destino->nombre }}
                                    </p>
                                </div>
                            </div>

                            <div class="summary-item">
                                <div class="summary-icon-small icon-calendar">
                                    <i class="fas fa-calendar-days"></i>
                                </div>

                                <div>
                                    <p class="summary-label mb-1">
                                        Fecha y hora
                                    </p>

                                    <p class="summary-text mb-0">
                                        {{ \Carbon\Carbon::parse($datos['fecha'])->format('d M, Y') }}
                                        <span class="text-warning mx-1">•</span>
                                        {{ $datos['hora'] }}
                                    </p>
                                </div>
                            </div>

                            <div class="summary-item">
                                <div class="summary-icon-small icon-lock">
                                    <i class="fas fa-lock"></i>
                                </div>

                                <div>
                                    <p class="summary-label mb-1">
                                        Tipo de servicio
                                    </p>

                                    <p class="summary-text mb-0">
                                        Traslado privado confirmado por DIY Antigua.
                                    </p>
                                </div>
                            </div>

                            <div class="price-total-box my-4">
                                <div>
                                    <span class="summary-label">
                                        Total a pagar
                                    </span>

                                    <p class="price-note mb-0">
                                        Precio final de la ruta
                                    </p>
                                </div>

                                <span class="price-total">
                                    Q{{ number_format($datos['precio'], 2) }}
                                </span>
                            </div>

                            <button type="button" id="open-email-verification" class="confirm-btn w-100" data-step="{{ $showPaymentStep ? 'payment' : 'details' }}">
                                {{ $showPaymentStep ? 'Confirmar y pagar' : 'Continuar al pago' }}
                                <i class="fas {{ $showPaymentStep ? 'fa-lock' : 'fa-arrow-right' }} ms-2"></i>
                            </button>

                            <div class="secure-note mt-4">
                                <i class="fas fa-shield-alt me-2"></i>
                                Reserva segura para DIY Antigua.
                            </div>

                            <a href="{{ url('/') }}#booking" class="change-link mt-3">
                                <i class="fas fa-arrow-left me-2"></i>
                                Cambiar datos del viaje
                            </a>
                        </aside>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="email-verification-modal" class="email-modal-backdrop d-none">
        <div class="email-modal-card">
            <button type="button" id="close-email-modal" class="email-modal-close">
                <i class="fas fa-times"></i>
            </button>

            <div class="email-modal-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>

            <span class="email-modal-kicker">
                Verificación de correo
            </span>

            <h3 class="email-modal-title">
                Ingresa el código enviado a tu correo
            </h3>

            <p class="email-modal-text">
                Enviaremos un código de 4 dígitos al correo que ingresaste. Esto nos ayuda a confirmar que podremos enviarte
                los detalles de tu reserva.
            </p>

            <div class="email-target-box">
                <i class="fas fa-envelope me-2"></i>
                <span id="email-preview">correo@ejemplo.com</span>
            </div>

            <div class="otp-inputs">
                <input type="text" maxlength="1" inputmode="numeric" class="otp-input">
                <input type="text" maxlength="1" inputmode="numeric" class="otp-input">
                <input type="text" maxlength="1" inputmode="numeric" class="otp-input">
                <input type="text" maxlength="1" inputmode="numeric" class="otp-input">
            </div>

            <p id="email-code-message" class="email-code-message d-none"></p>

            <button type="button" id="verify-email-code" class="confirm-btn w-100 mt-3">
                Verificar y continuar
                <i class="fas fa-check ms-2"></i>
            </button>

            <button type="button" id="resend-email-code" class="resend-code-btn">
                Reenviar código
            </button>
        </div>
    </div>

    @guest
        <div id="guest-choice-modal" class="email-modal-backdrop d-none">
            <div class="email-modal-card guest-choice-card">
                <button type="button" id="close-guest-choice-modal" class="email-modal-close">
                    <i class="fas fa-times"></i>
                </button>

                <div class="email-modal-icon">
                    <i class="fas fa-passport"></i>
                </div>

                <span class="email-modal-kicker">Reserva flexible</span>

                <h3 class="email-modal-title">Puedes reservar como invitado o iniciar sesión</h3>

                <p class="email-modal-text">
                    Si inicias sesión, guardaremos la reserva en tu cuenta. Si prefieres avanzar rápido, continúa como invitado y verificaremos tu correo.
                </p>

                <div class="d-grid gap-3 mt-4">
                    <a href="{{ route('login') }}" class="confirm-btn text-center text-decoration-none">
                        Iniciar sesión
                        <i class="fas fa-arrow-right-to-bracket ms-2"></i>
                    </a>

                    <button type="button" id="continue-as-guest" class="resend-code-btn">
                        Continuar como invitado
                    </button>
                </div>
            </div>
        </div>
    @endguest

    <link rel="stylesheet" href="{{ asset('css/reservas/detalles.css') }}">

    <style>
        .payment-premium-card {
            background:
                linear-gradient(135deg, rgba(255,255,255,.98), rgba(248,247,242,.96)),
                radial-gradient(circle at 8% 0%, rgba(252,202,0,.12), transparent 32%);
        }

        .payment-trust-strip {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .payment-trust-strip div {
            border: 1px solid rgba(54,54,54,.12);
            background: rgba(255,255,255,.76);
            border-radius: 14px;
            padding: 12px;
            font-size: 12px;
            font-weight: 900;
            color: #363636;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-align: center;
        }

        .payment-trust-strip i {
            color: #000000;
        }

        .payment-step-note {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(252,202,0,.12);
            border: 1px solid rgba(252,202,0,.34);
            color: #363636;
            font-size: 13px;
            font-weight: 800;
        }

        .payment-step-note i {
            color: #000000;
        }

        .guest-choice-card {
            max-width: 520px;
        }

        @media (max-width: 768px) {
            .payment-trust-strip {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('reservation-form');
            const openButton = document.getElementById('open-email-verification');
            const modal = document.getElementById('email-verification-modal');
            const closeButton = document.getElementById('close-email-modal');
            const verifyButton = document.getElementById('verify-email-code');
            const resendButton = document.getElementById('resend-email-code');
            const emailInput = document.querySelector('input[name="correo_cliente"]');
            const tokenInput = document.getElementById('email_verification_token');
            const emailPreview = document.getElementById('email-preview');
            const messageBox = document.getElementById('email-code-message');
            const otpInputs = document.querySelectorAll('.otp-input');
            const guestChoiceModal = document.getElementById('guest-choice-modal');
            const closeGuestChoiceModal = document.getElementById('close-guest-choice-modal');
            const continueAsGuestButton = document.getElementById('continue-as-guest');
            const fingerInput = document.getElementById('finger');
            const detailsStep = document.getElementById('details-step');
            const paymentStep = document.getElementById('payment-step');
            const continueToPaymentButton = document.getElementById('continue-to-payment');
            const paymentSubmitButton = document.getElementById('payment-submit-button');
            const backToDetailsButton = document.getElementById('back-to-details');
            const stepper = document.querySelector('.reservation-stepper');
            const paymentStepperItem = document.querySelector('.stepper-payment');
            let guestChoiceAccepted = false;
            let formSubmitting = false;
            let currentStep = openButton?.dataset.step || stepper?.dataset.currentStep || 'details';

            function showMessage(message, type = 'error') {
                messageBox.textContent = message;
                messageBox.classList.remove('d-none', 'error', 'success');
                messageBox.classList.add(type);
            }

            function clearMessage() {
                messageBox.textContent = '';
                messageBox.classList.add('d-none');
                messageBox.classList.remove('error', 'success');
            }

            function validateMainForm() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return false;
                }

                return true;
            }

            function validateTripDetails() {
                const fields = [
                    'nombre_cliente',
                    'correo_cliente',
                    'telefono_cliente',
                    'punto_recogida',
                    'punto_destino',
                    'fecha_viaje',
                    'hora_viaje',
                    'pasajeros',
                ];

                for (const field of fields) {
                    const input = form.querySelector(`[name="${field}"]`);

                    if (input && !input.checkValidity()) {
                        input.reportValidity();
                        input.focus();
                        return false;
                    }
                }

                return true;
            }

            function setPrimaryButtonForStep(step) {
                currentStep = step;
                openButton.dataset.step = step;

                if (step === 'payment') {
                    openButton.innerHTML = 'Confirmar y pagar <i class="fas fa-lock ms-2"></i>';
                } else {
                    openButton.innerHTML = 'Continuar al pago <i class="fas fa-arrow-right ms-2"></i>';
                }
            }

            function goToPaymentStep() {
                if (!validateTripDetails()) {
                    return;
                }

                detailsStep.classList.add('step-panel-leaving');

                setTimeout(() => {
                    detailsStep.classList.add('d-none');
                    detailsStep.classList.remove('step-panel-leaving');
                    paymentStep.classList.remove('d-none');
                    paymentStep.classList.add('step-panel-entering');
                    paymentStepperItem?.classList.add('active');
                    stepper?.setAttribute('data-current-step', 'payment');
                    setPrimaryButtonForStep('payment');
                    window.scrollTo({ top: Math.max(0, form.getBoundingClientRect().top + window.scrollY - 110), behavior: 'smooth' });

                    setTimeout(() => {
                        paymentStep.classList.remove('step-panel-entering');
                    }, 260);
                }, 180);
            }

            function goToDetailsStep() {
                paymentStep.classList.add('step-panel-leaving');

                setTimeout(() => {
                    paymentStep.classList.add('d-none');
                    paymentStep.classList.remove('step-panel-leaving');
                    detailsStep.classList.remove('d-none');
                    detailsStep.classList.add('step-panel-entering');
                    paymentStepperItem?.classList.remove('active');
                    stepper?.setAttribute('data-current-step', 'details');
                    setPrimaryButtonForStep('details');
                    window.scrollTo({ top: Math.max(0, form.getBoundingClientRect().top + window.scrollY - 110), behavior: 'smooth' });

                    setTimeout(() => {
                        detailsStep.classList.remove('step-panel-entering');
                    }, 260);
                }, 180);
            }

            function openModal() {
                const email = emailInput.value.trim();

                if (!email) {
                    emailInput.focus();
                    return;
                }

                emailPreview.textContent = email;
                modal.classList.remove('d-none');

                setTimeout(() => {
                    otpInputs[0].focus();
                }, 150);
            }

            function closeModal() {
                modal.classList.add('d-none');
                clearMessage();
            }

            function getOtpCode() {
                return Array.from(otpInputs).map(input => input.value).join('');
            }

            function clearOtp() {
                otpInputs.forEach(input => input.value = '');

                if (otpInputs[0]) {
                    otpInputs[0].focus();
                }
            }

            function setButtonLoading(button, loadingText) {
                button.disabled = true;
                button.dataset.originalHtml = button.innerHTML;
                button.innerHTML = loadingText;
            }

            function resetButton(button) {
                button.disabled = false;

                if (button.dataset.originalHtml) {
                    button.innerHTML = button.dataset.originalHtml;
                }
            }

            async function parseJsonResponse(response) {
                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    const errorMessage = data.message ||
                        Object.values(data.errors || {}).flat()[0] ||
                        'Ocurrió un error inesperado.';

                    throw new Error(errorMessage);
                }

                return data;
            }

            async function sendCode() {
                clearMessage();

                if (!validateMainForm()) {
                    return;
                }

                const email = emailInput.value.trim();

                setButtonLoading(openButton, 'Enviando código...');

                try {
                    const response = await fetch("{{ route('reservas.email-code.send') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            correo_cliente: email
                        })
                    });

                    await parseJsonResponse(response);

                    clearOtp();
                    openModal();
                    showMessage('Código enviado correctamente. Revisa tu correo.', 'success');

                } catch (error) {
                    openModal();
                    showMessage(error.message || 'Ocurrió un error enviando el código.');
                } finally {
                    resetButton(openButton);
                }
            }

            async function verifyCode() {
                clearMessage();

                const email = emailInput.value.trim();
                const code = getOtpCode();

                if (code.length !== 4) {
                    showMessage('Ingresa el código completo de 4 dígitos.');
                    return;
                }

                setButtonLoading(verifyButton, 'Verificando...');

                try {
                    const response = await fetch("{{ route('reservas.email-code.verify') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            correo_cliente: email,
                            codigo: code
                        })
                    });

                    const data = await parseJsonResponse(response);

                    if (!data.verification_token) {
                        throw new Error('No se recibió el token de verificación.');
                    }

                    tokenInput.value = data.verification_token;

                    showMessage('Correo verificado. Continuando con tu reserva...', 'success');

                    setTimeout(() => {
                        finalizeSubmit();
                    }, 600);

                } catch (error) {
                    showMessage(error.message || 'El código no es válido.');
                    clearOtp();
                } finally {
                    resetButton(verifyButton);
                }
            }

            const authenticatedEmail = @json(auth()->user()?->email ? strtolower(auth()->user()->email) : null);

            function showGuestChoiceIfNeeded() {
                if (authenticatedEmail || guestChoiceAccepted || !guestChoiceModal) {
                    return false;
                }

                if (!validateMainForm()) {
                    return true;
                }

                guestChoiceModal.classList.remove('d-none');

                return true;
            }

            function closeGuestChoice() {
                if (guestChoiceModal) {
                    guestChoiceModal.classList.add('d-none');
                }
            }

            function finalizeSubmit() {
                if (formSubmitting) {
                    return;
                }

                formSubmitting = true;
                openButton.disabled = true;
                openButton.innerHTML = 'Procesando reserva y pago...';
                form.submit();
            }

            function submitAuthenticatedReservationIfPossible() {
                if (!authenticatedEmail) {
                    return false;
                }

                if (!validateMainForm()) {
                    return true;
                }

                if (emailInput.value.trim().toLowerCase() !== authenticatedEmail) {
                    return false;
                }

                tokenInput.value = '';
                finalizeSubmit();

                return true;
            }

            openButton.addEventListener('click', function() {
                if (currentStep !== 'payment') {
                    goToPaymentStep();
                    return;
                }

                if (showGuestChoiceIfNeeded()) {
                    return;
                }

                if (submitAuthenticatedReservationIfPossible()) {
                    return;
                }

                sendCode();
            });

            continueToPaymentButton?.addEventListener('click', goToPaymentStep);
            backToDetailsButton?.addEventListener('click', goToDetailsStep);
            paymentSubmitButton?.addEventListener('click', function() {
                openButton.click();
            });
            closeButton.addEventListener('click', closeModal);
            resendButton.addEventListener('click', sendCode);
            verifyButton.addEventListener('click', verifyCode);

            if (closeGuestChoiceModal) {
                closeGuestChoiceModal.addEventListener('click', closeGuestChoice);
            }

            if (continueAsGuestButton) {
                continueAsGuestButton.addEventListener('click', function() {
                    guestChoiceAccepted = true;
                    closeGuestChoice();
                    sendCode();
                });
            }

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');

                    if (this.value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });

                input.addEventListener('paste', function(event) {
                    event.preventDefault();

                    const pasted = (event.clipboardData || window.clipboardData)
                        .getData('text')
                        .replace(/\D/g, '')
                        .slice(0, 4);

                    pasted.split('').forEach((digit, digitIndex) => {
                        if (otpInputs[digitIndex]) {
                            otpInputs[digitIndex].value = digit;
                        }
                    });

                    const nextIndex = Math.min(pasted.length, otpInputs.length - 1);

                    if (otpInputs[nextIndex]) {
                        otpInputs[nextIndex].focus();
                    }
                });

                input.addEventListener('keydown', function(event) {
                    if (event.key === 'Backspace' && !this.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }

                    if (event.key === 'Enter') {
                        verifyCode();
                    }
                });
            });

            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            if (guestChoiceModal) {
                guestChoiceModal.addEventListener('click', function(event) {
                    if (event.target === guestChoiceModal) {
                        closeGuestChoice();
                    }
                });
            }
        });

        function initFingerprintJS() {
            FingerprintJS.load({}).then(fp => fp.get()).then(data => {
                const input = document.getElementById('finger');
                if (input) {
                    input.value = data.visitorId;
                }
            });
        }
    </script>
    <script async src="https://fpcdn.io/v3/cUXvdB6hHu/iife.min.js" onload="initFingerprintJS()"></script>
@endsection

@extends('layouts.app')

@section('content')
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

                @php
                    $vehiculoSeleccionado = $ruta->vehiculosDisponibles->where('id', $datos['vehiculo_id'])->first();
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

                <div class="row g-4">
                    <div class="col-lg-8">
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

                        <section class="form-section-card">
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

                            <button type="button" id="open-email-verification" class="confirm-btn w-100">
                                Continuar con mi reserva
                                <i class="fas fa-arrow-right ms-2"></i>
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

    <link rel="stylesheet" href="{{ asset('css/reservas/detalles.css') }}">
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
                        form.submit();
                    }, 600);

                } catch (error) {
                    showMessage(error.message || 'El código no es válido.');
                    clearOtp();
                } finally {
                    resetButton(verifyButton);
                }
            }

            const authenticatedEmail = @json(auth()->user()?->email ? strtolower(auth()->user()->email) : null);

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
                openButton.disabled = true;
                openButton.innerHTML = 'Creando reserva...';
                form.submit();

                return true;
            }

            openButton.addEventListener('click', function() {
                if (submitAuthenticatedReservationIfPossible()) {
                    return;
                }

                sendCode();
            });
            closeButton.addEventListener('click', closeModal);
            resendButton.addEventListener('click', sendCode);
            verifyButton.addEventListener('click', verifyCode);

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
        });
    </script>
@endsection

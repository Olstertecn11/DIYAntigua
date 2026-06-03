@extends('layouts.app')

@section('content')
    <script src="https://h.online-metrix.net/fp/tags.js?org_id={{ $fingerprintOrgId }}&session_id={{ $fingerprintFullSessionId }}" type="application/javascript"></script>
    <noscript>
        <iframe style="width:100px;height:100px;border:0;position:absolute;top:-5000px;" src="https://h.online-metrix.net/fp/tags?org_id={{ $fingerprintOrgId }}&session_id={{ $fingerprintFullSessionId }}"></iframe>
    </noscript>

    <div class="min-vh-100 py-5" style="background:#050505;color:#fff;">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-7">
                    <div class="mb-4">
                        <span class="text-warning fw-bold text-uppercase small">Pago seguro</span>
                        <h1 class="display-6 fw-bold mt-2 mb-2">Completa el pago de tu reserva</h1>
                        <p class="text-secondary mb-0">Reserva {{ $reservacion->codigo_reserva }}</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 rounded-3">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('payments.store', $reservacion->codigo_reserva) }}" id="payment-form" autocomplete="off"
                        data-processing-kicker="Pago seguro"
                        data-processing-title="Procesando pago"
                        data-processing-message="No cierres esta ventana. Estamos comunicándonos con el procesador de pagos.">
                        @csrf
                        <input type="hidden" name="fingerprint_session_id" value="{{ $fingerprintSessionId }}">
                        <input type="hidden" name="finger" id="finger">

                        <div class="p-4 rounded-3 mb-4" style="background:#0d0d0d;border:1px solid #242424;">
                            <h2 class="h5 fw-bold mb-4">Tarjeta</h2>

                            <div class="mb-3">
                                <label class="form-label text-secondary">Nombre en la tarjeta</label>
                                <input type="text" name="cc_name" class="form-control form-control-lg bg-black text-white border-secondary" value="{{ old('cc_name') }}" required maxlength="120">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary">Número de tarjeta</label>
                                <input type="text" name="cc_number" class="form-control form-control-lg bg-black text-white border-secondary" inputmode="numeric" autocomplete="cc-number" required maxlength="23">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-secondary">Mes</label>
                                    <input type="text" name="cc_exp_month" class="form-control form-control-lg bg-black text-white border-secondary" inputmode="numeric" placeholder="MM" required maxlength="2" value="{{ old('cc_exp_month') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary">Año</label>
                                    <input type="text" name="cc_exp_year" class="form-control form-control-lg bg-black text-white border-secondary" inputmode="numeric" placeholder="YYYY" required maxlength="4" value="{{ old('cc_exp_year') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label text-secondary">CVV</label>
                                    <input type="password" name="cc_cvv2" class="form-control form-control-lg bg-black text-white border-secondary" inputmode="numeric" autocomplete="cc-csc" required maxlength="4">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label text-secondary">Tipo de tarjeta</label>
                                <select name="cc_type" class="form-select form-select-lg bg-black text-white border-secondary" required>
                                    <option value="visa" @selected(old('cc_type') === 'visa')>Visa</option>
                                    <option value="mastercard" @selected(old('cc_type') === 'mastercard')>Mastercard</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-4 rounded-3 mb-4" style="background:#0d0d0d;border:1px solid #242424;">
                            <h2 class="h5 fw-bold mb-4">Facturación</h2>

                            <div class="mb-3">
                                <label class="form-label text-secondary">Dirección</label>
                                <input type="text" name="billing_address" class="form-control form-control-lg bg-black text-white border-secondary" value="{{ old('billing_address', 'Guatemala') }}" required maxlength="191">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary">Ciudad</label>
                                    <input type="text" name="billing_city" class="form-control form-control-lg bg-black text-white border-secondary" value="{{ old('billing_city', 'Guatemala') }}" required maxlength="100">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary">Departamento</label>
                                    <input type="text" name="billing_state" class="form-control form-control-lg bg-black text-white border-secondary" value="{{ old('billing_state', 'Guatemala') }}" required maxlength="100">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary">País</label>
                                    <input type="text" name="billing_country" class="form-control form-control-lg bg-black text-white border-secondary" value="{{ old('billing_country', 'Guatemala') }}" required maxlength="100">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label text-secondary">Código postal</label>
                                    <input type="text" name="billing_zip" class="form-control form-control-lg bg-black text-white border-secondary" value="{{ old('billing_zip', '01001') }}" required maxlength="20">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill py-3" id="pay-button">
                            Pagar Q{{ number_format($reservacion->precio_total, 2) }}
                        </button>
                    </form>
                </div>

                <div class="col-lg-5">
                    <aside class="p-4 rounded-3 sticky-lg-top" style="background:#0d0d0d;border:1px solid #242424;top:24px;">
                        <h2 class="h5 fw-bold mb-4">Resumen</h2>

                        <div class="d-flex justify-content-between border-bottom border-secondary pb-3 mb-3">
                            <span class="text-secondary">Código</span>
                            <span class="fw-bold">{{ $reservacion->codigo_reserva }}</span>
                        </div>

                        <div class="d-flex justify-content-between border-bottom border-secondary pb-3 mb-3">
                            <span class="text-secondary">Pasajero</span>
                            <span class="fw-bold text-end">{{ $reservacion->nombre_cliente }}</span>
                        </div>

                        <div class="d-flex justify-content-between border-bottom border-secondary pb-3 mb-3">
                            <span class="text-secondary">Fecha</span>
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($reservacion->fecha_viaje)->format('d/m/Y') }} {{ $reservacion->hora_viaje }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <span class="text-secondary">Total</span>
                            <span class="display-6 fw-bold text-warning">Q{{ number_format($reservacion->precio_total, 2) }}</span>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function initFingerprintJS() {
            FingerprintJS.load({}).then(fp => fp.get()).then(data => {
                document.getElementById('finger').value = data.visitorId;
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('payment-form');
            const button = document.getElementById('pay-button');

            form.addEventListener('submit', function (event) {
                if (!navigator.onLine) {
                    event.preventDefault();
                    window.DIYProcessing?.offline('Tu conexión parece estar caída. No se envió el pago.');
                    return;
                }

                button.disabled = true;
                button.textContent = 'Procesando pago...';
            });
        });
    </script>
    <script async src="https://fpcdn.io/v3/cUXvdB6hHu/iife.min.js" onload="initFingerprintJS()"></script>
@endsection

<?php

namespace App\Http\Controllers\Public;

use App\Events\ReservaCreada;
use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use App\Models\Reservacion;
use App\Models\Ruta;
use App\Models\RutaVehiculo;
use App\Services\Affiliates\ReferralTracker;
use App\Services\Payments\PaymentManager;
use App\Support\PhoneNumber;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ReservaController extends Controller
{
    public function cotizar(Request $request)
    {
        if (! $request->hasAny(['origen', 'destino', 'fecha', 'hora', 'pasajeros'])) {
            return redirect(route('welcome') . '#booking');
        }

        $validated = $request->validate([
            'origen' => ['required', 'exists:lugares,id'],
            'destino' => ['required', 'exists:lugares,id', 'different:origen'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required'],
            'pasajeros' => ['required', 'integer', 'min:1', 'max:15'],
        ]);

        $ruta = Ruta::with([
            'origen',
            'destino',
            'vehiculosDisponibles' => fn ($query) => $query
                ->where('vehiculos.activo', true)
                ->orderBy('vehiculos.max_pasajeros'),
        ])
            ->where('origen_id', $validated['origen'])
            ->where('destino_id', $validated['destino'])
            ->where('activa', true)
            ->first();

        if (! $ruta) {
            return back()
                ->withErrors(['ruta' => 'No existe una ruta activa para el origen y destino seleccionados.'])
                ->withInput();
        }

        $datos = [
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'pasajeros' => $validated['pasajeros'],
        ];

        $pasajeros = (int) $validated['pasajeros'];
        $vehiculosCompatibles = $ruta->vehiculosDisponibles
            ->filter(fn ($vehiculo) => $pasajeros <= (int) $vehiculo->max_pasajeros)
            ->values();

        $ruta->setRelation('vehiculosDisponibles', $vehiculosCompatibles);

        $images = [
            'sedan' => asset('images/sedan_image.jpg'),
            'suv' => asset('images/suv_image.jpg'),
            'van' => asset('images/micro_image.jpg'),
        ];

        return Inertia::render('Reservas/Cotizar', [
            'ruta' => $this->formatRutaForInertia($ruta),
            'datos' => $datos,
            'images' => $images,
            'urls' => [
                'home' => route('welcome'),
                'detalles' => route('reservas.detalles'),
            ],
        ]);
    }

    public function detalles(Request $request)
    {
        if (! $request->hasAny(['ruta_id', 'vehiculo_id', 'id_detalle_ruta', 'fecha', 'hora', 'pasajeros'])) {
            return redirect(route('welcome') . '#booking');
        }

        $validated = $request->validate([
            'ruta_id' => ['required', 'exists:rutas,id'],
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'id_detalle_ruta' => ['required', 'integer', 'exists:ruta_vehiculo,id'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required'],
            'pasajeros' => ['required', 'integer', 'min:1', 'max:15'],
            'precio' => ['nullable', 'numeric', 'min:0'],
        ]);

        $ruta = Ruta::with([
            'origen',
            'destino',
            'vehiculosDisponibles' => fn ($query) => $query->where('vehiculos.activo', true),
        ])
            ->where('activa', true)
            ->findOrFail($validated['ruta_id']);

        $detalleRuta = RutaVehiculo::whereKey($validated['id_detalle_ruta'])
            ->where('ruta_id', $ruta->id)
            ->where('vehiculo_id', $validated['vehiculo_id'])
            ->first();

        $vehiculoSeleccionado = $ruta->vehiculosDisponibles
                                     ->where('id', (int) $validated['vehiculo_id'])
                                     ->first();

        if (! $detalleRuta || ! $vehiculoSeleccionado) {
            return redirect()
                ->route('reservas.cotizar', [
                    'origen' => $ruta->origen_id,
                    'destino' => $ruta->destino_id,
                    'fecha' => $validated['fecha'],
                    'hora' => $validated['hora'],
                    'pasajeros' => $validated['pasajeros'],
                ])
                ->withErrors(['vehiculo_id' => 'El vehículo seleccionado no está disponible para esta ruta.']);
        }

        if ($validated['pasajeros'] > $vehiculoSeleccionado->max_pasajeros) {
            return redirect()
                ->route('reservas.cotizar', [
                    'origen' => $ruta->origen_id,
                    'destino' => $ruta->destino_id,
                    'fecha' => $validated['fecha'],
                    'hora' => $validated['hora'],
                    'pasajeros' => $validated['pasajeros'],
                ])
                ->withErrors(['pasajeros' => 'La cantidad de pasajeros supera la capacidad del vehículo seleccionado.']);
        }

        $datos = [
            ...$validated,
            'precio' => (float) $detalleRuta->precio_tarifa,
            'id_detalle_ruta' => (int) $detalleRuta->id,
        ];
        $fingerprintSessionId = 'DIYQ' . Str::upper(Str::random(24));
        $fingerprintFullSessionId = config('qpaypro.fingerprint_prefix') . $fingerprintSessionId;
        $fingerprintOrgId = config('qpaypro.fingerprint_org_id');

        $phone = PhoneNumber::split(old('telefono_cliente', auth()->user()?->telefono));

        return Inertia::render('Reservas/Detalles', [
            'ruta' => $this->formatRutaForInertia($ruta),
            'datos' => $datos,
            'vehiculoSeleccionado' => $vehiculoSeleccionado ? [
                'id' => $vehiculoSeleccionado->id,
                'nombre' => $vehiculoSeleccionado->nombre,
                'max_pasajeros' => (int) $vehiculoSeleccionado->max_pasajeros,
            ] : null,
            'countries' => $this->phoneCountries(),
            'defaults' => [
                'nombre_cliente' => old('nombre_cliente', auth()->user()?->name),
                'correo_cliente' => old('correo_cliente', auth()->user()?->email),
                'telefono_country_code' => old('telefono_country_code', $phone['country']),
                'telefono_national' => old('telefono_national', $phone['number']),
            ],
            'paymentDefaults' => [
                'cc_name' => old('cc_name', ''),
                'cc_exp_month' => old('cc_exp_month', ''),
                'cc_exp_year' => old('cc_exp_year', ''),
                'cc_type' => old('cc_type', 'visa'),
                'billing_country' => old('billing_country', 'Guatemala'),
            ],
            'fingerprint' => [
                'sessionId' => $fingerprintSessionId,
                'fullSessionId' => $fingerprintFullSessionId,
                'orgId' => $fingerprintOrgId,
            ],
            'authenticatedEmail' => auth()->user()?->email ? strtolower(auth()->user()->email) : null,
            'urls' => [
                'store' => route('reservas.store'),
                'login' => route('login'),
                'cotizar' => route('reservas.cotizar'),
                'sendEmailCode' => route('reservas.email-code.send'),
                'verifyEmailCode' => route('reservas.email-code.verify'),
            ],
        ]);
    }

    public function store(Request $request, PaymentManager $paymentManager, ReferralTracker $referrals)
    {
        $validated = $request->validate([
            'ruta_id' => ['required', 'exists:rutas,id'],
            'vehiculo_id' => ['required', 'exists:vehiculos,id'],
            'id_detalle_ruta' => ['required', 'integer', 'exists:ruta_vehiculo,id'],
            'nombre_cliente' => ['required', 'string', 'max:150'],
            'correo_cliente' => ['required', 'email', 'max:150'],
            'telefono_country_code' => ['required', 'string', 'in:' . implode(',', array_keys(config('phone.countries', [])))],
            'telefono_national' => ['required', 'string', 'max:30', 'regex:/^[0-9\s().-]{5,30}$/'],
            'punto_recogida' => ['required', 'string'],
            'punto_destino' => ['required', 'string'],
            'notas_adicionales' => ['nullable', 'string'],
            'fecha_viaje' => ['required', 'date', 'after_or_equal:today'],
            'hora_viaje' => ['required'],
            'pasajeros' => ['required', 'integer', 'min:1', 'max:15'],
            'precio_total' => ['required', 'numeric', 'min:0'],
            'email_verification_token' => ['nullable', 'string', 'max:64'],
            'cc_name' => ['required', 'string', 'max:120'],
            'cc_number' => ['required', 'string', 'regex:/^[0-9\s-]{13,23}$/'],
            'cc_exp_month' => ['required', 'regex:/^(0[1-9]|1[0-2])$/'],
            'cc_exp_year' => ['required', 'digits:4', 'integer', 'min:' . now()->year, 'max:' . now()->addYears(20)->year],
            'cc_cvv2' => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
            'cc_type' => ['required', 'in:visa,mastercard'],
            'billing_address' => ['required', 'string', 'max:191'],
            'billing_city' => ['required', 'string', 'max:100'],
            'billing_state' => ['required', 'string', 'max:100'],
            'billing_country' => ['required', 'string', 'max:100'],
            'billing_zip' => ['required', 'string', 'max:20'],
            'finger' => ['nullable', 'string', 'max:191'],
            'fingerprint_session_id' => ['required', 'string', 'max:191'],
        ]);

        $email = strtolower(trim($validated['correo_cliente']));
        $authenticatedEmail = auth()->check() && strtolower((string) auth()->user()->email) === $email;
        $verification = null;

        if (! $authenticatedEmail) {
            if (empty($validated['email_verification_token']) || strlen($validated['email_verification_token']) !== 64) {
                return back()
                    ->withErrors(['correo_cliente' => 'Verifica tu correo antes de continuar con la reserva.'])
                    ->withInput();
            }

            $verification = EmailVerificationCode::where('email', $email)
                ->where('purpose', 'reservation')
                ->where('verification_token_hash', hash('sha256', $validated['email_verification_token']))
                ->whereNotNull('verified_at')
                ->whereNull('used_at')
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            if (! $verification) {
                return back()
                    ->withErrors(['correo_cliente' => 'La verificación de correo es inválida o venció. Solicita un nuevo código.'])
                    ->withInput();
            }
        }

        $lock = Cache::lock('reservation-submit:' . sha1((string) $request->session()->getId()), 120);

        if (! $lock->get()) {
            return back()
                ->withErrors(['reserva' => 'Ya estamos procesando tu reserva y pago. Espera un momento.'])
                ->withInput();
        }

        try {
            $reserva = DB::transaction(function () use ($validated, $verification, $email, $request, $referrals) {
                $ruta = Ruta::with([
                    'vehiculosDisponibles' => fn ($query) => $query->where('vehiculos.activo', true),
                ])
                    ->where('activa', true)
                    ->findOrFail($validated['ruta_id']);

                $detalleRuta = RutaVehiculo::whereKey($validated['id_detalle_ruta'])
                    ->where('ruta_id', $ruta->id)
                    ->where('vehiculo_id', $validated['vehiculo_id'])
                    ->first();

                $vehiculo = $detalleRuta
                    ? $ruta->vehiculosDisponibles->where('id', (int) $validated['vehiculo_id'])->first()
                    : null;

                if (! $vehiculo) {
                    throw ValidationException::withMessages([
                        'vehiculo_id' => 'El vehículo seleccionado no está disponible para esta ruta.',
                    ]);
                }

                if ($validated['pasajeros'] > $vehiculo->max_pasajeros) {
                    throw ValidationException::withMessages([
                        'pasajeros' => 'La cantidad de pasajeros supera la capacidad del vehículo seleccionado.',
                    ]);
                }

                $notasCompletas = "RECOGIDA: {$validated['punto_recogida']}\n"
                    . "DESTINO: {$validated['punto_destino']}\n"
                    . "NOTAS: " . ($validated['notas_adicionales'] ?? 'Ninguna');

                $socioId = $referrals->currentSocioId($request);
                $precioTotal = (float) $detalleRuta->precio_tarifa;

                $reserva = Reservacion::create([
                    'codigo_reserva' => $this->generarCodigoReserva(),
                    'ruta_id' => $validated['ruta_id'],
                    'user_id' => auth()->id(),
                    'socio_id' => $socioId,
                    'fecha_viaje' => $validated['fecha_viaje'],
                    'hora_viaje' => $validated['hora_viaje'],
                    'pasajeros' => $validated['pasajeros'],
                    'tipo_vehiculo' => strtolower($vehiculo->nombre),
                    'nombre_cliente' => $validated['nombre_cliente'],
                    'correo_cliente' => $email,
                    'telefono_cliente' => PhoneNumber::format($validated['telefono_country_code'], $validated['telefono_national']),
                    'notas_adicionales' => $notasCompletas,
                    'precio_total' => $precioTotal,
                    'comision_socio' => $referrals->commissionFor($socioId, $precioTotal),
                    'estado_pago' => 'pendiente',
                    'estado_viaje' => 'programado',
                ]);

                if ($verification) {
                    $verification->update([
                        'used_at' => now(),
                    ]);
                }

                DB::afterCommit(function () use ($reserva) {
                    event(new ReservaCreada($reserva));
                });

                return $reserva;
            });

            $transaction = $paymentManager->pay($reserva, [
                'cc_name' => $validated['cc_name'],
                'cc_number' => preg_replace('/\D+/', '', $validated['cc_number']),
                'cc_exp_month' => $validated['cc_exp_month'],
                'cc_exp_year' => $validated['cc_exp_year'],
                'cc_cvv2' => $validated['cc_cvv2'],
                'cc_type' => strtolower($validated['cc_type']),
                'billing_address' => $validated['billing_address'],
                'billing_city' => $validated['billing_city'],
                'billing_state' => $validated['billing_state'],
                'billing_country' => $validated['billing_country'],
                'billing_zip' => $validated['billing_zip'],
                'finger' => $validated['finger'] ?: '',
                'fingerprint_session_id' => $validated['fingerprint_session_id'],
            ], $request);

            if ($transaction->status === 'approved') {
                $referrals->clear($request);
            }

            return redirect(URL::signedRoute('payments.result', ['transaction' => $transaction]));
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()
                ->withErrors(['reserva' => 'Error al procesar la reserva: ' . $e->getMessage()])
                ->withInput();
        } finally {
            optional($lock)->release();
        }
    }

    public function confirmar($codigo)
    {
        $reservacion = Reservacion::with([
            'ruta.origen',
            'ruta.destino',
            'paymentTransactions' => fn ($query) => $query->latest(),
        ])
            ->where('codigo_reserva', $codigo)
            ->firstOrFail();

        return Inertia::render('Reservas/Confirmar', [
            'reserva' => $this->formatReservacionForInertia($reservacion),
            'urls' => [
                'home' => route('welcome'),
                'pdf' => route('reservas.pdf', $reservacion->codigo_reserva),
                'checkout' => route('payments.checkout', $reservacion->codigo_reserva),
            ],
        ]);
    }

    public function descargarPDF($codigo)
    {
        $reserva = Reservacion::with([
            'ruta.origen',
            'ruta.destino',
            'paymentTransactions' => fn ($query) => $query->latest(),
        ])
            ->where('codigo_reserva', $codigo)
            ->firstOrFail();

        $logoBase64 = $this->localImageDataUri(public_path('images/logo.png'));
        $qrPayload = route('reservas.confirmar', $reserva->codigo_reserva);
        $qrBase64 = $this->remoteImageDataUri('https://api.qrserver.com/v1/create-qr-code/?size=220x220&margin=12&data=' . urlencode($qrPayload));
        $transaction = $reserva->paymentTransactions->first();

        $pdf = Pdf::loadView('reservas.comprobante_pdf', compact('reserva', 'logoBase64', 'qrBase64', 'transaction'));
        $pdf->setPaper('letter', 'portrait');

        return $pdf->download("Comprobante-{$reserva->codigo_reserva}.pdf");
    }

    private function localImageDataUri(string $path): string
    {
        if (! file_exists($path)) {
            return '';
        }

        $type = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($type) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            default => 'image/' . $type,
        };

        return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($path));
    }

    private function remoteImageDataUri(string $url): string
    {
        try {
            $response = Http::timeout(8)->get($url);

            if (! $response->successful()) {
                return '';
            }

            $contentType = $response->header('Content-Type') ?: 'image/png';

            return 'data:' . $contentType . ';base64,' . base64_encode($response->body());
        } catch (\Throwable) {
            return '';
        }
    }

    private function generarCodigoReserva()
    {
        do {
            $codigo = 'DIY-' . strtoupper(Str::random(8));
        } while (Reservacion::where('codigo_reserva', $codigo)->exists());

        return $codigo;
    }

    private function formatRutaForInertia(Ruta $ruta): array
    {
        return [
            'id' => $ruta->id,
            'origen_id' => $ruta->origen_id,
            'destino_id' => $ruta->destino_id,
            'origen' => $ruta->origen ? [
                'id' => $ruta->origen->id,
                'nombre' => $ruta->origen->nombre,
                'ciudad' => $ruta->origen->ciudad,
            ] : null,
            'destino' => $ruta->destino ? [
                'id' => $ruta->destino->id,
                'nombre' => $ruta->destino->nombre,
                'ciudad' => $ruta->destino->ciudad,
            ] : null,
            'vehiculos_disponibles' => $ruta->vehiculosDisponibles
                ->map(fn ($vehiculo) => [
                    'id' => $vehiculo->id,
                    'nombre' => $vehiculo->nombre,
                    'max_pasajeros' => (int) $vehiculo->max_pasajeros,
                    'precio_tarifa' => (float) $vehiculo->pivot->precio_tarifa,
                    'ruta_vehiculo_id' => (int) $vehiculo->pivot->id,
                ])
                ->values()
                ->all(),
        ];
    }

    private function phoneCountries(): array
    {
        return collect(config('phone.countries', []))
            ->map(fn (array $country, string $code) => [
                'code' => $code,
                'name' => $country['name'],
                'dial' => $country['dial'],
            ])
            ->values()
            ->all();
    }

    private function formatReservacionForInertia(Reservacion $reserva): array
    {
        $travelAt = $reserva->travelDateTime();
        $transaction = $reserva->paymentTransactions->first();

        return [
            'id' => $reserva->id,
            'codigo_reserva' => $reserva->codigo_reserva,
            'nombre_cliente' => $reserva->nombre_cliente,
            'correo_cliente' => $reserva->correo_cliente,
            'telefono_cliente' => $reserva->telefono_cliente,
            'notas_adicionales' => $reserva->notas_adicionales,
            'fecha' => $travelAt->format('d/m/Y'),
            'hora' => $travelAt->format('H:i'),
            'travel_at_iso' => $travelAt->toIso8601String(),
            'tipo_vehiculo' => strtoupper((string) $reserva->tipo_vehiculo),
            'pasajeros' => (int) $reserva->pasajeros,
            'precio_total' => (float) $reserva->precio_total,
            'estado_pago' => $reserva->estado_pago,
            'estado_viaje' => $reserva->estado_viaje,
            'created_at' => $reserva->created_at?->format('d/m/Y H:i'),
            'ruta' => [
                'origen' => $reserva->ruta?->origen?->nombre,
                'destino' => $reserva->ruta?->destino?->nombre,
            ],
            'transaction' => $transaction ? [
                'provider_transaction_id' => $transaction->provider_transaction_id,
                'reference' => $transaction->reference,
                'card_brand' => $transaction->card_brand,
                'card_last_four' => $transaction->card_last_four,
            ] : null,
            'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=10&data=' . urlencode(route('reservas.confirmar', $reserva->codigo_reserva)),
        ];
    }
}

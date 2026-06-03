<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ReservaEmailVerificationController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'correo_cliente' => ['required', 'email', 'max:150'],
        ]);

        $email = strtolower(trim($validated['correo_cliente']));
        $mailConfigurationError = $this->mailConfigurationError();

        if ($mailConfigurationError) {
            Log::warning('El correo de verificacion de reserva no esta configurado para entrega real.', [
                'reason' => $mailConfigurationError,
                'mailer' => config('mail.default'),
                'from' => config('mail.from.address'),
            ]);

            return response()->json([
                'message' => 'El envío de correos no está configurado correctamente. Contacta al administrador.',
            ], 503);
        }

        $rateLimitKey = 'reservation-email-code:' . $request->ip() . ':' . $email;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return response()->json([
                'message' => 'Has solicitado demasiados códigos. Intenta nuevamente en unos minutos.',
            ], 429);
        }

        RateLimiter::hit($rateLimitKey, 300);

        EmailVerificationCode::where('email', $email)
            ->where('purpose', 'reservation')
            ->whereNull('used_at')
            ->update([
                'used_at' => now(),
            ]);

        $code = (string) random_int(1000, 9999);

        $verification = EmailVerificationCode::create([
            'email' => $email,
            'code_hash' => Hash::make($code),
            'purpose' => 'reservation',
            'attempts' => 0,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::send('emails.reservas.codigo-verificacion', [
                'code' => $code,
                'email' => $email,
            ], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Código de verificación - DIY Antigua');
            });
        } catch (\Throwable $exception) {
            $verification->update(['used_at' => now()]);
            RateLimiter::clear($rateLimitKey);

            Log::error('No se pudo enviar el codigo de verificacion de reserva.', [
                'email_hash' => hash('sha256', $email),
                'mailer' => config('mail.default'),
                'from' => config('mail.from.address'),
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'No pudimos enviar el código en este momento. Revisa la configuración de correo o intenta nuevamente.',
            ], 503);
        }

        return response()->json([
            'message' => 'Código enviado correctamente.',
        ]);
    }

    private function mailConfigurationError(): ?string
    {
        $mailer = (string) config('mail.default');
        $from = (string) config('mail.from.address');

        if (in_array($mailer, ['log', 'array'], true)) {
            return 'non_delivery_mailer';
        }

        if ($from === '' || $from === 'hello@example.com') {
            return 'invalid_from_address';
        }

        if ($mailer === 'smtp') {
            $host = (string) config('mail.mailers.smtp.host');
            $username = (string) config('mail.mailers.smtp.username');
            $password = (string) config('mail.mailers.smtp.password');

            if ($host === '' || $username === '' || $password === '') {
                return 'incomplete_smtp_credentials';
            }
        }

        if ($mailer === 'resend') {
            if (! config('services.resend.key')) {
                return 'missing_resend_api_key';
            }

            $fromDomain = strtolower((string) str($from)->after('@'));
            $publicDomains = ['gmail.com', 'hotmail.com', 'outlook.com', 'yahoo.com', 'icloud.com', 'example.com', 'resend.dev'];

            if ($fromDomain === '' || in_array($fromDomain, $publicDomains, true)) {
                return 'resend_from_domain_not_verified';
            }
        }

        return null;
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'correo_cliente' => ['required', 'email', 'max:150'],
            'codigo' => ['required', 'digits:4'],
        ]);

        $email = strtolower(trim($validated['correo_cliente']));

        $verification = EmailVerificationCode::where('email', $email)
            ->where('purpose', 'reservation')
            ->whereNull('used_at')
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $verification) {
            return response()->json([
                'message' => 'Primero solicita un código válido de verificación.',
            ], 422);
        }

        if ($verification->attempts >= 3) {
            $verification->update([
                'used_at' => now(),
            ]);

            return response()->json([
                'message' => 'Has superado el número máximo de intentos. Solicita un nuevo código.',
            ], 422);
        }

        if (! Hash::check($validated['codigo'], $verification->code_hash)) {
            $verification->increment('attempts');

            return response()->json([
                'message' => 'El código ingresado no es correcto.',
            ], 422);
        }

        $token = Str::random(64);

        $verification->update([
            'verified_at' => now(),
            'verification_token_hash' => hash('sha256', $token),
        ]);

        return response()->json([
            'message' => 'Correo verificado correctamente.',
            'verification_token' => $token,
        ]);
    }
}

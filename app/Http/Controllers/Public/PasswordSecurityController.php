<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class PasswordSecurityController extends Controller
{
    public function edit(Request $request)
    {
        return Inertia::render('Profile/Security', [
            'user' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
            'urls' => [
                'profile' => route('profile.edit'),
                'send' => route('profile.security.send'),
                'verify' => route('profile.security.verify'),
                'update' => route('profile.security.update'),
            ],
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        $email = strtolower(trim($user->email));
        $rateLimitKey = "password-security-code:{$user->id}:{$request->ip()}";

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return response()->json([
                'message' => 'Has solicitado demasiados códigos. Intenta nuevamente en unos minutos.',
            ], 429);
        }

        RateLimiter::hit($rateLimitKey, 300);

        EmailVerificationCode::forEmail($email)
            ->forPurpose('password_change')
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        $code = (string) random_int(100000, 999999);
        $verification = EmailVerificationCode::create([
            'email' => $email,
            'code_hash' => Hash::make($code),
            'purpose' => 'password_change',
            'attempts' => 0,
            'ip_address' => $request->ip(),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
            'expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::send('emails.profile.password-code', [
                'code' => $code,
                'name' => $user->name,
            ], function ($message) use ($email) {
                $message->to($email)->subject('Código para cambiar tu contraseña - DYANTIGUA');
            });
        } catch (\Throwable $exception) {
            $verification->update(['used_at' => now()]);
            RateLimiter::clear($rateLimitKey);
            report($exception);

            return response()->json([
                'message' => 'No pudimos enviar el código. Intenta nuevamente en unos minutos.',
            ], 503);
        }

        return response()->json([
            'message' => 'Enviamos un código de 6 dígitos a tu correo.',
            'expires_in' => 600,
        ]);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $verification = EmailVerificationCode::forEmail($request->user()->email)
            ->forPurpose('password_change')
            ->active()
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $verification) {
            return response()->json(['message' => 'Solicita un código nuevo para continuar.'], 422);
        }

        if ($verification->attempts >= 5) {
            $verification->update(['used_at' => now()]);

            return response()->json(['message' => 'Superaste el número de intentos. Solicita otro código.'], 422);
        }

        if (! Hash::check($validated['code'], $verification->code_hash)) {
            $verification->increment('attempts');

            return response()->json(['message' => 'El código ingresado no es correcto.'], 422);
        }

        $token = Str::random(64);
        $verification->update([
            'verified_at' => now(),
            'verification_token_hash' => hash('sha256', $token),
        ]);

        return response()->json([
            'message' => 'Código verificado. Ya puedes crear tu nueva contraseña.',
            'verification_token' => $token,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'verification_token' => ['required', 'string', 'size:64'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $verification = EmailVerificationCode::forEmail($request->user()->email)
            ->forPurpose('password_change')
            ->active()
            ->verified()
            ->where('verification_token_hash', hash('sha256', $validated['verification_token']))
            ->latest()
            ->first();

        if (! $verification) {
            return back()->withErrors([
                'verification_token' => 'La verificación venció o ya fue utilizada. Solicita un código nuevo.',
            ]);
        }

        $request->user()->update(['password' => $validated['password']]);
        $verification->update(['used_at' => now()]);

        return redirect()->route('profile.edit')->with('success', 'Tu contraseña fue actualizada correctamente.');
    }
}

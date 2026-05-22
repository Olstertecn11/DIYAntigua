<?php

namespace App\Services\Payments;

use App\Models\PaymentSecurityEvent;
use Illuminate\Http\Request;

class PaymentSecurityService
{
    public function hashIp(?string $ip): ?string
    {
        return $ip ? hash_hmac('sha256', $ip, config('app.key')) : null;
    }

    public function hashUserAgent(?string $userAgent): ?string
    {
        return $userAgent ? hash_hmac('sha256', $userAgent, config('app.key')) : null;
    }

    public function record(Request $request, string $eventType, string $severity, ?int $reservacionId = null, ?string $description = null, array $metadata = []): void
    {
        PaymentSecurityEvent::create([
            'reservacion_id' => $reservacionId,
            'user_id' => $request->user()?->id,
            'event_type' => $eventType,
            'severity' => $severity,
            'description' => $description,
            'ip_hash' => $this->hashIp($request->ip()),
            'user_agent_hash' => $this->hashUserAgent($request->userAgent()),
            'metadata' => $metadata,
        ]);
    }
}

<?php

namespace App\Services\Payments;

use App\Models\Reservacion;
use Illuminate\Support\Str;

class PaymentFingerprintService
{
    public function ensureSessionId(Reservacion $reservacion): string
    {
        $sessionId = session("payment_fingerprint.{$reservacion->id}");

        if (! $sessionId) {
            $sessionId = 'DIY' . $reservacion->id . Str::upper(Str::random(24));
            session(["payment_fingerprint.{$reservacion->id}" => $sessionId]);
        }

        return $sessionId;
    }
}

<?php

namespace App\Services\Payments;

use App\Enums\Payments\PaymentProvider;
use App\Enums\Payments\PaymentStatus;
use App\Events\PagoAprobado;
use App\Models\PaymentAttempt;
use App\Models\PaymentTransaction;
use App\Models\Reservacion;
use App\Services\Payments\Providers\QPayPro\QPayProClient;
use App\Services\Payments\Providers\QPayPro\QPayProPayloadBuilder;
use App\Services\Payments\Providers\QPayPro\QPayProResponseMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentManager
{
    public function __construct(
        private readonly QPayProClient $client,
        private readonly QPayProPayloadBuilder $payloadBuilder,
        private readonly QPayProResponseMapper $responseMapper,
        private readonly PaymentSanitizer $sanitizer,
        private readonly PaymentSecurityService $securityService,
    ) {
    }

    public function pay(Reservacion $reservacion, array $cardData, Request $request): PaymentTransaction
    {
        $attempt = DB::transaction(function () use ($reservacion, $cardData, $request) {
            $lockedReservation = Reservacion::whereKey($reservacion->id)->lockForUpdate()->firstOrFail();

            if ($lockedReservation->estado_pago === 'pagado') {
                $this->securityService->record($request, 'duplicate_paid_payment_attempt', 'medium', $lockedReservation->id);
                abort(409, 'Esta reserva ya fue pagada.');
            }

            if ($lockedReservation->estado_pago === 'procesando') {
                $recentAttempt = PaymentAttempt::where('reservacion_id', $lockedReservation->id)
                    ->where('status', PaymentStatus::Processing->value)
                    ->where('created_at', '>=', now()->subMinutes(5))
                    ->latest()
                    ->first();

                if ($recentAttempt) {
                    $this->securityService->record($request, 'duplicate_processing_payment_attempt', 'medium', $lockedReservation->id);
                    abort(409, 'El pago de esta reserva ya se está procesando.');
                }
            }

            $attempt = PaymentAttempt::create([
                'reservacion_id' => $lockedReservation->id,
                'user_id' => $request->user()?->id,
                'provider' => PaymentProvider::QPayPro->value,
                'flow' => 'direct_card',
                'status' => PaymentStatus::Processing->value,
                'amount' => $lockedReservation->precio_total,
                'currency' => config('qpaypro.currency'),
                'fingerprint_session_id' => $cardData['fingerprint_session_id'],
                'idempotency_key' => (string) Str::uuid(),
                'client_ip_hash' => $this->securityService->hashIp($request->ip()),
                'user_agent_hash' => $this->securityService->hashUserAgent($request->userAgent()),
                'started_at' => now(),
            ]);

            $lockedReservation->update([
                'estado_pago' => 'procesando',
                'pago_provider' => PaymentProvider::QPayPro->value,
                'pago_error_mensaje' => null,
            ]);

            return $attempt;
        });

        $freshReservation = Reservacion::findOrFail($reservacion->id);
        $payload = $this->payloadBuilder->build($freshReservation, $attempt, $cardData, $cardData['finger']);

        try {
            $clientResponse = $this->client->charge($payload);
        } catch (\Throwable $exception) {
            $clientResponse = [
                'ok' => false,
                'status' => 0,
                'body' => [
                    'message' => 'No fue posible conectar con QPayPro.',
                    'exception' => class_basename($exception),
                ],
            ];
        }

        $transaction = DB::transaction(function () use ($attempt, $freshReservation, $cardData, $clientResponse) {
            $lockedReservation = Reservacion::whereKey($freshReservation->id)->lockForUpdate()->firstOrFail();
            $mapped = $this->responseMapper->map($clientResponse);
            $status = $mapped['status'];

            $transaction = PaymentTransaction::create([
                'payment_attempt_id' => $attempt->id,
                'reservacion_id' => $lockedReservation->id,
                'provider' => PaymentProvider::QPayPro->value,
                'provider_transaction_id' => $mapped['provider_transaction_id'],
                'authorization_code' => $mapped['authorization_code'],
                'reference' => $mapped['reference'] ?: $lockedReservation->codigo_reserva,
                'status' => $status,
                'response_code' => $mapped['response_code'],
                'response_message' => $mapped['response_message'],
                'amount' => $lockedReservation->precio_total,
                'currency' => config('qpaypro.currency'),
                'card_brand' => strtolower($cardData['cc_type']),
                'card_last_four' => $this->sanitizer->lastFour($cardData['cc_number']),
                'raw_response_sanitized' => $this->sanitizer->sanitize($mapped['raw']),
            ]);

            $attempt->update([
                'status' => $status,
                'finished_at' => now(),
            ]);

            $this->updateReservationStatus($lockedReservation, $transaction);

            return $transaction;
        });

        if ($transaction->status === PaymentStatus::Approved->value) {
            event(new PagoAprobado($transaction->reservacion, $transaction));
        }

        return $transaction;
    }

    private function updateReservationStatus(Reservacion $reservacion, PaymentTransaction $transaction): void
    {
        $paymentStatus = match ($transaction->status) {
            PaymentStatus::Approved->value => 'pagado',
            PaymentStatus::Declined->value => 'rechazado',
            PaymentStatus::Error->value => 'fallido',
            default => 'procesando',
        };

        $reservacion->update([
            'estado_pago' => $paymentStatus,
            'pago_referencia' => $transaction->provider_transaction_id ?: $transaction->reference,
            'pagado_at' => $paymentStatus === 'pagado' ? now() : null,
            'pago_error_mensaje' => in_array($paymentStatus, ['rechazado', 'fallido'], true) ? $transaction->response_message : null,
        ]);
    }
}

<?php

namespace App\Services\Payments\Providers\QPayPro;

use App\Enums\Payments\PaymentStatus;

class QPayProResponseMapper
{
    public function map(array $clientResponse): array
    {
        $body = $clientResponse['body'] ?? [];
        $statusText = strtolower((string) ($body['status'] ?? $body['response'] ?? $body['responseCode'] ?? $body['x_response_code'] ?? $body['code'] ?? ''));
        $message = (string) ($body['message'] ?? $body['responseText'] ?? $body['x_response_reason_text'] ?? $body['raw_body'] ?? '');
        $code = (string) ($body['response_code'] ?? $body['responseCode'] ?? $body['x_response_code'] ?? $body['code'] ?? $clientResponse['status'] ?? '');

        $approved = ($body['result'] ?? null) === 1 || $this->containsAny($statusText . ' ' . strtolower($message), ['approved', 'aprobado', 'success', 'successful', 'transacción exitosa']) || $code === '1';
        $declined = $this->containsAny($statusText . ' ' . strtolower($message), ['declined', 'rechazado', 'denied', 'failed']) || in_array($code, ['2', '3'], true);

        $status = match (true) {
            $approved => PaymentStatus::Approved->value,
            $declined => PaymentStatus::Declined->value,
            $clientResponse['ok'] === false => PaymentStatus::Error->value,
            default => PaymentStatus::UnderReview->value,
        };

        return [
            'status' => $status,
            'provider_transaction_id' => $body['transaction_id'] ?? $body['idTransaction'] ?? $body['x_trans_id'] ?? $body['id'] ?? null,
            'authorization_code' => $body['authorization_code'] ?? $body['x_auth_code'] ?? null,
            'reference' => $body['reference'] ?? $body['x_invoice_num'] ?? null,
            'response_code' => $code,
            'response_message' => mb_substr($message, 0, 500),
            'raw' => $body,
        ];
    }

    private function containsAny(string $value, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (str_contains($value, $needle)) {
                return true;
            }
        }

        return false;
    }
}

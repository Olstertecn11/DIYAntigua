<?php

namespace App\Services\Payments;

class PaymentSanitizer
{
    private array $sensitiveKeys = [
        'cc_number',
        'cc_cvv2',
        'cvv',
        'cvc',
        'card_number',
        'x_private_key',
        'x_api_secret',
        'number',
        'security_code',
    ];

    public function sanitize(array $payload): array
    {
        return collect($payload)
            ->mapWithKeys(fn ($value, $key) => [$key => $this->sanitizeValue((string) $key, $value)])
            ->all();
    }

    private function sanitizeValue(string $key, mixed $value): mixed
    {
        if (in_array(strtolower($key), $this->sensitiveKeys, true)) {
            return '[filtered]';
        }

        if (is_array($value)) {
            return $this->sanitize($value);
        }

        if (is_string($value)) {
            return mb_substr($value, 0, 500);
        }

        return $value;
    }

    public function lastFour(string $cardNumber): string
    {
        $number = preg_replace('/\D+/', '', $cardNumber);

        return substr($number, -4) ?: '';
    }
}

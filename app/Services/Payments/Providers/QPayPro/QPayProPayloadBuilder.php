<?php

namespace App\Services\Payments\Providers\QPayPro;

use App\Models\PaymentAttempt;
use App\Models\Reservacion;
use Illuminate\Support\Str;

class QPayProPayloadBuilder
{
    public function build(Reservacion $reservacion, PaymentAttempt $attempt, array $cardData, string $finger): array
    {
        [$firstName, $lastName] = $this->splitName($reservacion->nombre_cliente);

        $payload = [
            'x_login' => config('qpaypro.merchant_id'),
            'x_private_key' => config('qpaypro.private_key'),
            'x_api_secret' => config('qpaypro.api_secret'),
            'x_product_id' => (int) config('qpaypro.product_id'),
            'x_audit_number' => random_int(100000, 999999),
            'x_fp_sequence' => random_int(1000000000, 2147483647),
            'x_fp_timestamp' => now()->timestamp,
            'x_invoice_num' => $reservacion->codigo_reserva,
            'x_description' => "Reserva {$reservacion->codigo_reserva}",
            'x_currency_code' => config('qpaypro.currency'),
            'x_amount' => (float) number_format((float) $reservacion->precio_total, 2, '.', ''),
            'x_line_item' => $this->lineItem($reservacion),
            'x_freight' => 0.00,
            'x_email' => $reservacion->correo_cliente,
            'cc_number' => preg_replace('/\D+/', '', $cardData['cc_number']),
            'cc_exp' => $this->expiration($cardData['cc_exp_month'], $cardData['cc_exp_year']),
            'cc_cvv2' => $cardData['cc_cvv2'],
            'cc_name' => $cardData['cc_name'],
            'cc_type' => strtolower($cardData['cc_type']),
            'x_first_name' => $firstName,
            'x_last_name' => $lastName,
            'x_company' => 'DYANTIGUA',
            'x_address' => $cardData['billing_address'],
            'x_city' => $cardData['billing_city'],
            'x_state' => $cardData['billing_state'],
            'x_country' => $cardData['billing_country'],
            'x_zip' => $cardData['billing_zip'],
            'x_relay_response' => 'none',
            'x_relay_url' => 'none',
            'x_type' => config('qpaypro.transaction_type'),
            'x_method' => 'CC',
            'http_origin' => config('app.url'),
            'visaencuotas' => 0,
            'payment_response_url' => [
                'success_url' => route('payments.return'),
                'error_url' => route('payments.return'),
            ],
            'origen' => config('qpaypro.origin'),
            'finger' => $finger,
        ];

        if (config('qpaypro.send_device_fingerprint_id')) {
            $payload['device_fingerprint_id'] = $attempt->fingerprint_session_id;
        }

        if ($customFields = $this->configuredJson('custom_fields')) {
            $payload['custom_fields'] = json_encode($customFields);
        }

        if ($firstTokenCustomFields = $this->configuredJson('first_token_custom_fields')) {
            $payload['first_token_custom_fields'] = $firstTokenCustomFields;
        }

        return $payload;
    }

    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name), 2);

        return [$parts[0] ?? 'Cliente', $parts[1] ?? $parts[0] ?? 'DYANTIGUA'];
    }

    private function expiration(string $month, string $year): string
    {
        return str_pad($month, 2, '0', STR_PAD_LEFT) . '/' . substr($year, -2);
    }

    private function lineItem(Reservacion $reservacion): string
    {
        $description = Str::limit("Reserva {$reservacion->codigo_reserva}", 40, '');

        return "{$description}<|>{$reservacion->codigo_reserva}<|><|>1<|>" . number_format((float) $reservacion->precio_total, 2, '.', '') . '<|>N';
    }

    private function configuredJson(string $key): ?array
    {
        $value = config("qpaypro.{$key}");

        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : null;
    }
}

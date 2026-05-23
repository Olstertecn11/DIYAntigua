<?php

namespace App\Services\Payments\Providers\QPayPro;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class QPayProClient
{
    public function charge(array $payload): array
    {
        $response = Http::acceptJson()
            ->timeout(config('qpaypro.timeout'))
            ->withOptions(['verify' => config('qpaypro.verify_ssl')])
            ->post(config('qpaypro.endpoint'), $payload);

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'body' => $this->decode($response->body()),
        ];
    }

    private function decode(string $body): array
    {
        $json = json_decode($body, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
            return $json;
        }

        parse_str($body, $parsed);

        if (is_array($parsed) && count($parsed) > 0) {
            return $parsed;
        }

        return ['raw_body' => mb_substr($body, 0, 1000)];
    }
}

<?php

namespace App\Support;

class PhoneNumber
{
    public static function format(?string $country, ?string $number): ?string
    {
        $country = strtoupper(trim((string) $country));
        $digits = preg_replace('/\D+/', '', (string) $number);
        $dial = config("phone.countries.{$country}.dial");

        if (! $dial || $digits === '') {
            return null;
        }

        return $dial . ' ' . $digits;
    }

    public static function split(?string $value): array
    {
        $value = trim((string) $value);
        $countries = collect(config('phone.countries', []))
            ->map(fn ($country, $code) => ['code' => $code, 'dial' => $country['dial']])
            ->sortByDesc(fn ($country) => strlen($country['dial']));

        foreach ($countries as $country) {
            if (str_starts_with($value, $country['dial'])) {
                return [
                    'country' => $country['code'],
                    'number' => trim(substr($value, strlen($country['dial']))),
                ];
            }
        }

        return [
            'country' => 'GT',
            'number' => $value,
        ];
    }
}

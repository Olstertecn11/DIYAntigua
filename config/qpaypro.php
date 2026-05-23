<?php

return [
    'environment' => env('QPAYPRO_ENVIRONMENT', 'sandbox'),
    'endpoint' => env('QPAYPRO_ENDPOINT', 'https://api-sandboxpayments.qpaypro.com/checkout/api_v1'),
    'merchant_id' => env('QPAYPRO_MERCHANT_ID', env('QPAYPRO_LOGIN')),
    'public_key' => env('QPAYPRO_PUBLIC_KEY'),
    'login' => env('QPAYPRO_MERCHANT_ID', env('QPAYPRO_LOGIN')),
    'private_key' => env('QPAYPRO_PRIVATE_KEY'),
    'api_secret' => env('QPAYPRO_API_SECRET'),
    'product_id' => env('QPAYPRO_PRODUCT_ID', '6'),
    'currency' => env('QPAYPRO_CURRENCY', 'GTQ'),
    'transaction_type' => env('QPAYPRO_TRANSACTION_TYPE', 'AUTH_ONLY'),
    'origin' => env('QPAYPRO_ORIGIN', 'TESTAPI'),
    'custom_fields' => env('QPAYPRO_CUSTOM_FIELDS'),
    'first_token_custom_fields' => env('QPAYPRO_FIRST_TOKEN_CUSTOM_FIELDS'),
    'fingerprint_org_id' => env('QPAYPRO_FINGERPRINT_ORG_ID', '1snn5n9w'),
    'fingerprint_prefix' => env('QPAYPRO_FINGERPRINT_PREFIX') ?: env('QPAYPRO_MERCHANT_ID', env('QPAYPRO_LOGIN')),
    'send_device_fingerprint_id' => (bool) env('QPAYPRO_SEND_DEVICE_FINGERPRINT_ID', false),
    'timeout' => (int) env('QPAYPRO_TIMEOUT', 45),
    'verify_ssl' => (bool) env('QPAYPRO_VERIFY_SSL', true),
];

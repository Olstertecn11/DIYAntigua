<?php

return [
    'environment' => env('QPAYPRO_ENVIRONMENT', 'sandbox'),
    'endpoint' => env('QPAYPRO_ENDPOINT', 'https://sandboxpayments.qpaypro.com/checkout/api_v1'),
    'login' => env('QPAYPRO_LOGIN'),
    'private_key' => env('QPAYPRO_PRIVATE_KEY'),
    'api_secret' => env('QPAYPRO_API_SECRET'),
    'product_id' => env('QPAYPRO_PRODUCT_ID', '6'),
    'currency' => env('QPAYPRO_CURRENCY', 'GTQ'),
    'transaction_type' => env('QPAYPRO_TRANSACTION_TYPE', 'AUTH_CAPTURE'),
    'fingerprint_org_id' => env('QPAYPRO_FINGERPRINT_ORG_ID', '1snn5n9w'),
    'fingerprint_prefix' => env('QPAYPRO_FINGERPRINT_PREFIX', env('QPAYPRO_LOGIN')),
    'timeout' => (int) env('QPAYPRO_TIMEOUT', 45),
    'verify_ssl' => (bool) env('QPAYPRO_VERIFY_SSL', true),
];

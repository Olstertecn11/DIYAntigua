<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Cambiamos esto para que Inertia controle el título dinámicamente de forma nativa -->
    <title inertia>{{ config('app.name', 'DYANTIGUA') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Montserrat:400,500,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    @viteReactRefresh
    @vite(['resources/js/app.js', 'resources/css/global.css'])
    @inertiaHead
</head>

<body>
    @inertia

    @php
        $localBusinessSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'TaxiService',
            'name' => 'DYANTIGUA',
            'url' => url('/'),
            'provider' => [
                '@type' => 'LocalBusiness',
                'name' => 'DYANTIGUA',
                'image' => asset('images/logo.png'),
                'telephone' => '+50235977809',
                'priceRange' => '$$',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Antigua Guatemala',
                    'addressCountry' => 'GT',
                ],
            ],
            'areaServed' => [
                ['@type' => 'Place', 'name' => 'Guatemala City Airport (GUA)'],
                ['@type' => 'Place', 'name' => 'Antigua Guatemala'],
                ['@type' => 'Place', 'name' => 'Panajachel'],
                ['@type' => 'Place', 'name' => 'Quetzaltenango'],
            ],
        ];
    @endphp

    <!-- Schema Markup global para SEO Local -->
    <script type="application/ld+json">@json($localBusinessSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
</body>

</html>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Servicio no disponible | {{ config('app.name', 'DYANTIGUA') }}</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Arial, Helvetica, sans-serif;
            background: #f6f7f9;
            color: #17202a;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        main {
            width: min(100%, 520px);
            text-align: center;
        }

        img {
            width: 88px;
            height: 88px;
            object-fit: contain;
            margin-bottom: 24px;
        }

        h1 {
            margin: 0 0 12px;
            font-size: clamp(28px, 5vw, 40px);
            line-height: 1.1;
        }

        p {
            margin: 0;
            color: #52606d;
            font-size: 18px;
            line-height: 1.55;
        }
    </style>
</head>

<body>
    <main>
        <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'DYANTIGUA') }}">
        <h1>Estamos revisando el servicio</h1>
        <p>Ha ocurrido un error interno. Por favor intenta mas tarde.</p>
    </main>
</body>

</html>

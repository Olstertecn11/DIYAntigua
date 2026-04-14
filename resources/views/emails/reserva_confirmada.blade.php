<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #050505;
            color: #ffffff;
            padding: 20px;
            margin: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .logo-container {
            text-align: center;
            padding: 20px 0;
        }
        .logo {
            max-width: 180px;
            height: auto;
        }
        .card {
            background-color: #0a0a0a;
            border: 1px solid #262626;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        h1 {
            color: #ffffff;
            font-size: 24px;
            margin-top: 0;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .highlight {
            color: #ffc107;
            font-weight: bold;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 25px 0;
        }
        li {
            padding: 10px 0;
            border-bottom: 1px solid #1a1a1a;
            color: #d1d1d1;
        }
        .detalles-traslado {
            background-color: #111111;
            padding: 15px;
            border-radius: 8px;
            border-left: 3px solid #ffc107;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.75rem;
            color: #555;
            text-align: center;
            line-height: 1.5;
        }
        .btn {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 12px;
            background-color: #ffffff;
            color: #000000;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            border-radius: 25px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-container">
            <img src="https://github.com/Olstertecn11/DIYAntigua/blob/main/public/images/logo.png?raw=true" alt="DiyAntigua Logo" class="logo">
        </div>

        <div class="card">
            <h1>¡Reserva Confirmada!</h1>
            <p style="text-align: center; color: #aaaaaa;">Hola, <strong>{{ $reserva->nombre_cliente }}</strong>.</p>
            <p>Tu traslado ha sido programado con éxito. Hemos reservado una unidad exclusivamente para ti.</p>

            <ul>
                <li><strong>Código de Reserva:</strong> <span class="highlight">{{ $reserva->codigo_reserva }}</span></li>
                <li><strong>Fecha de Viaje:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_viaje)->format('d/m/Y') }}</li>
                <li><strong>Hora de Encuentro:</strong> {{ $reserva->hora_viaje }}</li>
                <li><strong>Tipo de Vehículo:</strong> {{ Str::upper($reserva->tipo_vehiculo) }}</li>
                <li><strong>Total a Pagar:</strong> <span style="color: #28a745; font-weight: bold;">Q{{ number_format($reserva->precio_total, 2) }}</span></li>
            </ul>

            <div class="detalles-traslado">
                <p style="margin-top: 0; font-weight: bold; color: #ffc107;">Puntos de Traslado:</p>
                <div style="color: #eee; font-size: 0.9rem;">
                    {!! nl2br(e($reserva->notas_adicionales)) !!}
                </div>
            </div>

            <a href="#" class="btn">Ver mi Reserva</a>

            <div class="footer">
                &copy; {{ date('Y') }} DiyAntigua Transport & Logistics.<br>
                Este es un correo automático, por favor no respondas a este mensaje.
            </div>
        </div>
    </div>
</body>
</html>

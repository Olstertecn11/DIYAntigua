<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Reserva - {{ $reserva->codigo_reserva }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { width: 150px; }
        .title { font-size: 20px; font-weight: bold; text-transform: uppercase; margin-top: 10px; }
        .info-section { margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { background-color: #f2f2f2; text-align: left; padding: 8px; font-size: 12px; text-transform: uppercase; }
        .table td { padding: 10px 8px; border-bottom: 1px solid #eee; font-size: 14px; }
        .highlight { color: #d4af37; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
        .badge { background: #000; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{public_path('images/logo.png')}}" class="logo">
        <div class="title">Comprobante de Reservación</div>
        <p>Código: <span class="highlight">{{ $reserva->codigo_reserva }}</span></p>
    </div>

    <div class="info-section">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Datos del Cliente</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Nombre:</strong> {{ $reserva->nombre_cliente }}</td>
                    <td><strong>Teléfono:</strong> {{ $reserva->telefono_cliente }}</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Email:</strong> {{ $reserva->correo_cliente }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-section">
        <table class="table">
            <thead>
                <tr>
                    <th colspan="2">Detalles del Traslado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Ruta:</strong> {{ $reserva->ruta->origen->nombre }} a {{ $reserva->ruta->destino->nombre }}</td>
                    <td><strong>Vehículo:</strong> {{ strtoupper($reserva->tipo_vehiculo) }}</td>
                </tr>
                <tr>
                    <td><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->fecha_viaje)->format('d/m/Y') }}</td>
                    <td><strong>Hora:</strong> {{ $reserva->hora_viaje }}</td>
                </tr>
                <tr>
                    <td><strong>Pasajeros:</strong> {{ $reserva->pasajeros }}</td>
                    <td><strong>Estado de Pago:</strong> {{ strtoupper($reserva->estado_pago) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
        <strong>Instrucciones adicionales:</strong><br>
        <p style="font-size: 12px;">{!! nl2br(e($reserva->notas_adicionales)) !!}</p>
    </div>

    <div style="text-align: right; margin-top: 20px;">
        <span style="font-size: 18px; font-weight: bold;">Total Pagado: Q{{ number_format($reserva->precio_total, 2) }}</span>
    </div>

    <div class="footer">
        DiyAntigua - Servicios de Transporte Privado en Guatemala.<br>
        Este documento sirve como comprobante de reserva. Presente este código al conductor.
    </div>
</body>
</html>

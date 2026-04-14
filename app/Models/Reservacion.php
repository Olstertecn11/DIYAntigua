<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservacion extends Model
{
    use SoftDeletes;

    protected $table = 'reservaciones';

    protected $fillable = [
        'codigo_reserva', 'ruta_id', 'user_id', 'socio_id', 'fecha_viaje',
        'hora_viaje', 'pasajeros', 'tipo_vehiculo', 'nombre_cliente',
        'correo_cliente', 'telefono_cliente', 'notas_adicionales',
        'precio_total', 'estado_pago', 'estado_viaje'
    ];
}

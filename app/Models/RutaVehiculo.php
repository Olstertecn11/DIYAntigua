<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RutaVehiculo extends Pivot
{
    protected $table = 'ruta_vehiculo';

    public $incrementing = true;

    protected $fillable = [
        'ruta_id',
        'vehiculo_id',
        'precio_tarifa'
    ];
}

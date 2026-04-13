<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $fillable = [
        'origen_id',
        'destino_id',
        'kilometraje',
        'activa'
    ];

    // Relación con el lugar de origen
    public function origen()
    {
        return $this->belongsTo(Lugar::class, 'origen_id');
    }

    // Relación con el lugar de destino
    public function destino()
    {
        return $this->belongsTo(Lugar::class, 'destino_id');
    }

    // Relación muchos a muchos con Vehiculos (Detalle de la ruta)
    public function vehiculosDisponibles()
    {
        return $this->belongsToMany(Vehiculo::class, 'ruta_vehiculo')
                    ->withPivot('id', 'precio_tarifa')
                    ->withTimestamps();
    }
}

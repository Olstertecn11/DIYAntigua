<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $fillable = [
        'nombre',
        'min_pasajeros',
        'max_pasajeros',
        'icono',
        'activo'
    ];

    // Relación con las rutas a través de la tabla pivote
    public function rutas()
    {
        return $this->belongsToMany(Ruta::class, 'ruta_vehiculo')
                    ->withPivot('precio_tarifa')
                    ->withTimestamps();
    }
}

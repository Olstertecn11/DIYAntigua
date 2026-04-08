<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    protected $table = 'conductores';

    protected $fillable = [
        'nombre',
        'telefono',
        'vehiculo_modelo',
        'placa',
        'estado' // activo, inactivo, en viaje
    ];
}

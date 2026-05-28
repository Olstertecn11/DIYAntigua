<?php

namespace App\Models;

use App\Models\Concerns\UsesHashidRouteKey;
use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    use UsesHashidRouteKey;

    protected $table = 'conductores';

    protected $fillable = [
        'nombre',
        'telefono',
        'vehiculo_modelo',
        'placa',
        'estado' // activo, inactivo, en viaje
    ];
}

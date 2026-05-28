<?php

namespace App\Models;

use App\Models\Concerns\UsesHashidRouteKey;
use Illuminate\Database\Eloquent\Model;

class Lugar extends Model
{
    use UsesHashidRouteKey;

    protected $table = 'lugares';
    protected $fillable = ['nombre', 'ciudad', 'estado'];

    // Relación: Un lugar puede ser origen de muchas rutas
    public function rutasComoOrigen()
    {
        return $this->hasMany(Ruta::class, 'origen_id');
    }

    // Relación: Un lugar puede ser destino de muchas rutas
    public function rutasComoDestino()
    {
        return $this->hasMany(Ruta::class, 'destino_id');
    }
}

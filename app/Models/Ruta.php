<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    protected $table = 'rutas';

    protected $fillable = [
        'origen_id',
        'destino_id',
        'kilometraje',
        'precio_sedan',
        'precio_suv',
        'precio_bus',
        'permite_sedan',
        'permite_suv',
        'permite_bus',
        'activa'
    ];

    // Casting para que los checkboxes funcionen como booleanos
    protected $casts = [
        'permite_sedan' => 'boolean',
        'permite_suv' => 'boolean',
        'permite_bus' => 'boolean',
        'activa' => 'boolean',
    ];

    public function origen()
    {
        return $this->belongsTo(Lugar::class, 'origen_id');
    }

    public function destino()
    {
        return $this->belongsTo(Lugar::class, 'destino_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id', 'codigo_referido', 'nombre_comercial', 'nit', 'telefono_negocio',
    'direccion', 'comision_porcentaje', 'activo', 'metodo_pago', 'titular_pago',
    'cuenta_pago'
])]
class AfiliadoInfo extends Model
{
    protected $table = 'afiliados_info';

    protected $casts = [
        'activo' => 'boolean',
        'comision_porcentaje' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

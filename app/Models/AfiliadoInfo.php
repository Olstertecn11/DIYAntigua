<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'nombre_comercial', 'nit', 'telefono_negocio', 'direccion', 'comision_porcentaje', 'activo'])]
class AfiliadoInfo extends Model
{
    protected $table = 'afiliados_info';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

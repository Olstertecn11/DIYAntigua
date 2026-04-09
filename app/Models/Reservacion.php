<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservacion extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * El nombre de la tabla asociado al modelo.
     *
     * @var string
     */
    protected $table = 'reservaciones';

    /**
     * Los atributos que son asignables (Mass Assignment).
     *
     * @var array
     */
    protected $fillable = [
        'codigo_reserva',
        'ruta_id',
        'user_id',
        'socio_id',
        'fecha_viaje',
        'hora_viaje',
        'pasajeros',
        'tipo_vehiculo',
        'nombre_cliente',
        'correo_cliente',
        'telefono_cliente',
        'notas_adicionales',
        'precio_total',
        'comision_socio',
        'estado_pago',
        'estado_viaje',
        'activo',
    ];

    /**
     * Los atributos que deben ser convertidos a fechas (Carbon).
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'fecha_viaje'];

    /**
     * Valores por defecto para los atributos de la tabla.
     */
    protected $attributes = [
        'estado_pago' => 'pendiente',
        'estado_viaje' => 'programado',
        'activo' => 1,
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    /**
     * Obtener la ruta asociada a la reservación.
     */
    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    /**
     * Obtener el usuario que realizó la reserva (si está registrado).
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtener el socio/afiliado que refirió la reserva.
     */
    public function socio()
    {
        return $this->belongsTo(User::class, 'socio_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (Filtros comunes)
    |--------------------------------------------------------------------------
    */

    public function scopeActivas($query)
    {
        return $query->where('activo', 1);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado_pago', 'pendiente');
    }
}

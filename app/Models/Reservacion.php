<?php
namespace App\Models;

use App\Models\Concerns\UsesHashidRouteKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Reservacion extends Model
{
    use SoftDeletes;
    use UsesHashidRouteKey;

    protected $table = 'reservaciones';

    protected $fillable = [
        'codigo_reserva', 'ruta_id', 'user_id', 'socio_id', 'fecha_viaje',
        'hora_viaje', 'pasajeros', 'tipo_vehiculo', 'nombre_cliente',
        'correo_cliente', 'telefono_cliente', 'notas_adicionales',
        'precio_total', 'comision_socio', 'estado_pago', 'estado_viaje', 'pago_provider',
        'pago_referencia', 'pagado_at', 'pago_error_mensaje',
        'cancelado_at', 'cancelado_por', 'motivo_cancelacion',
        'reembolso_estado', 'reembolso_monto', 'reembolso_solicitado_at'
    ];

    protected $casts = [
        'fecha_viaje' => 'date',
        'pagado_at' => 'datetime',
        'cancelado_at' => 'datetime',
        'reembolso_solicitado_at' => 'datetime',
        'precio_total' => 'decimal:2',
        'comision_socio' => 'decimal:2',
        'reembolso_monto' => 'decimal:2',
    ];

    /**
     * Relación con la Ruta
     */
    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    /**
     * Relación con el Socio (opcional, si aplica)
     */
    public function socio(): BelongsTo
    {
        return $this->belongsTo(User::class, 'socio_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function canceladoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelado_por');
    }

    public function paymentAttempts(): HasMany
    {
        return $this->hasMany(PaymentAttempt::class);
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function travelDateTime(): Carbon
    {
        return Carbon::parse(Carbon::parse($this->fecha_viaje)->format('Y-m-d') . ' ' . $this->hora_viaje);
    }

    public function hoursUntilTravel(): int
    {
        return (int) max(0, now()->diffInHours($this->travelDateTime(), false));
    }

    public function canBeCancelledWithRefund(): bool
    {
        return $this->estado_viaje !== 'cancelado'
            && now()->lt($this->travelDateTime()->subHours(24));
    }
}

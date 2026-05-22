<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentAttempt extends Model
{
    protected $fillable = [
        'reservacion_id',
        'user_id',
        'provider',
        'flow',
        'status',
        'amount',
        'currency',
        'fingerprint_session_id',
        'idempotency_key',
        'client_ip_hash',
        'user_agent_hash',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function reservacion(): BelongsTo
    {
        return $this->belongsTo(Reservacion::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}

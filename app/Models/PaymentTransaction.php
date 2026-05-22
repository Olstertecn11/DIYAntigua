<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'payment_attempt_id',
        'reservacion_id',
        'provider',
        'provider_transaction_id',
        'authorization_code',
        'reference',
        'status',
        'response_code',
        'response_message',
        'amount',
        'currency',
        'card_brand',
        'card_last_four',
        'raw_response_sanitized',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'raw_response_sanitized' => 'array',
        'verified_at' => 'datetime',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(PaymentAttempt::class, 'payment_attempt_id');
    }

    public function reservacion(): BelongsTo
    {
        return $this->belongsTo(Reservacion::class);
    }
}

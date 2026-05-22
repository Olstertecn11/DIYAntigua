<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCallback extends Model
{
    protected $fillable = [
        'provider',
        'reservacion_id',
        'payment_attempt_id',
        'payment_transaction_id',
        'event_type',
        'status',
        'payload_sanitized',
        'signature_valid',
        'processed',
        'processed_at',
    ];

    protected $casts = [
        'payload_sanitized' => 'array',
        'signature_valid' => 'boolean',
        'processed' => 'boolean',
        'processed_at' => 'datetime',
    ];
}

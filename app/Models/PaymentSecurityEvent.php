<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSecurityEvent extends Model
{
    protected $fillable = [
        'reservacion_id',
        'user_id',
        'event_type',
        'severity',
        'description',
        'ip_hash',
        'user_agent_hash',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}

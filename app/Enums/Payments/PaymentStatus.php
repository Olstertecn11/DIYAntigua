<?php

namespace App\Enums\Payments;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Approved = 'approved';
    case Declined = 'declined';
    case Error = 'error';
    case Cancelled = 'cancelled';
    case UnderReview = 'under_review';
}

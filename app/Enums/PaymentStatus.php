<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case DONE = 'done';
    case REFUSED = 'refused';
    case REFUNDED = 'refunded';
    case REFUND_REQUESTED = 'refund_requested';

    public function isPeddingStatus(): bool
    {
        return in_array($this, [self::PENDING, self::REFUND_REQUESTED]);
    }
}

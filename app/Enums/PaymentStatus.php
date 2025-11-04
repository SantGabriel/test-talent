<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case DONE = 'done';
    case REFUSED = 'refused';
    case REFUNDED = 'refunded';
}

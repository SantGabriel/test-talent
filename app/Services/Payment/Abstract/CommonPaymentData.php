<?php

namespace App\Services\Payment\Abstract;

use App\Enums\PaymentStatus;

readonly class CommonPaymentData
{

    public string $id;
    public PaymentStatus $status;
    public float|int $amount;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->status = $data['status'];
        $this->amount = $data['amount'];
    }
}
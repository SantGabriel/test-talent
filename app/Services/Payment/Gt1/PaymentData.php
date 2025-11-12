<?php

namespace App\Services\Payment\Gt1;

use App\Enums\PaymentStatus;

readonly class PaymentData
{
    public string $id;
    public string $name;
    public string $email;
    public PaymentStatus $status;
    public string $card_first_digits;
    public string $card_last_digits;
    public float|int $amount;

    public function __construct(array $params)
    {
        $this->id = $params['id'];
        $this->name = $params['name'];
        $this->email = $params['email'];
        $this->status = $params['status'];
        $this->card_first_digits = $params['card_first_digits'];
        $this->card_last_digits = $params['card_last_digits'];
        $this->amount = $params['amount'];
    }
}
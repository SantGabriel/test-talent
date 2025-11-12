<?php

namespace App\Services\Payment\GtFake;

readonly class PaymentData
{
    public string $id;
    public string $email;
    public array $items;
    public string $status;
    public string $card_hash;
    public float|int $amount;
    public function __construct(array $params)
    {
        $this->id = $params['id'];
        $this->items = $params['items'];
        $this->email = $params['email'];
        $this->status = $params['status'];
        $this->card_hash = $params['card_hash'];
        $this->amount = $params['amount'];
    }
}
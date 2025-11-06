<?php

namespace App\Services\Payment;

use App\Models\Gateway;

abstract class AbstractPaymentGateway
{

    protected string|null $id_token;

    protected string $gt_preffix;

    public function __construct(
        protected Gateway $gateway,
        protected string $base_url
    ) {
    }
    /**
     * Process a payment transaction
     *
     * @return mixed
     */
    abstract public function transaction(): mixed;

    /**
     * Process a refund
     *
     * @return mixed
     */
    abstract public function refund(): mixed;

    /**
     * Get list of transactions
     *
     * @return array
     */
    abstract public function listTransactions(): array;

    /**
     * Authenticate with the payment gateway
     *
     * @return void
     */
    abstract public function login(): void;

    abstract public function defaultAuthHeader(): array;
}
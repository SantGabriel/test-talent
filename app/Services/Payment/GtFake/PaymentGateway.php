<?php

namespace App\Services\Payment\GtFake;

use App\DTO\ProductDTO;
use App\Enums\PaymentStatus;
use App\Models\Client;
use App\Models\Gateway;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use App\Services\Payment\Abstract\CommonPaymentData;
use App\Services\Payment\Abstract\AbstractPaymentGateway;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PaymentGateway extends AbstractPaymentGateway
{
    private string $secret;
    private string $token;
    public function __construct(
        public Gateway $gateway,
        protected string $base_url = "http://fake_url"
    )
    {
        parent::__construct($gateway, $base_url);
        $this->gt_preffix = 'GATEWAY_GTFAKE_';
        $this->secret = config("gateway.{$this->gt_preffix}SECRET");
        $this->token = config("gateway.{$this->gt_preffix}TOKEN");
        $this->id_token = Cache::get("{$this->gt_preffix}auth_token");
        $this->login();
    }

    /**
     *
     * @param Collection<ProductDTO> $productDTOList
     */
    public function transaction(Collection $productDTOList, Client $client, string $cardNumber, string $cvv): ?Transaction
    {
        $totalValue = $this->calculateValue($productDTOList);
        $transaction = Transaction::create([
            'client' => $client->id,
            'external_id' => "lalala",
            'amount' => $totalValue,
            'gateway' => $this->gateway->id,
            'status' => PaymentStatus::REFUSED->value,
            'card_last_numbers' => substr($cardNumber,-4)
        ]);
        $this->checkChangeStatus($transaction);

        foreach ($productDTOList as $productDTO) {
            TransactionProduct::create([
                'product_id' => $productDTO->id,
                'transaction_id' => $transaction->id,
                'quantity' => $productDTO->quantity
            ]);
        }

        return $transaction;
    }

    public function convertStatus(string $status): PaymentStatus
    {
        return match ($status) {
            "pending" => PaymentStatus::PENDING,
            "paid" => PaymentStatus::DONE,
            "canceled" => PaymentStatus::REFUSED,
            "refunded" => PaymentStatus::REFUNDED,
            "refund_requested" => PaymentStatus::REFUND_REQUESTED,
        };
    }

    public function getPaymentData(string $external_id): ?CommonPaymentData
    {
        return null;
    }

    public function refund(int $id): ?Transaction
    {
        $transaction = Transaction::find($id);
        $transaction->update(['status' => PaymentStatus::REFUND_REQUESTED]);
        return $transaction;
    }

    public function listTransactions(): array
    {
        return [];
    }

    public function login(): void
    {
        // nao precisa
        return;
    }

    public function defaultAuthHeader(): array
    {
        return [];
    }
}
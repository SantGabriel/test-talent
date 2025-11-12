<?php

namespace App\Services\Payment\Gt2;

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
        protected string $base_url = "http://gt:3002"
    )
    {
        parent::__construct($gateway, $base_url);
        $this->gt_preffix = 'GATEWAY_GT2_';
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
        $totalValue100 = $totalValue * 100;
        $response = Http::withHeaders($this->defaultAuthHeader())
            ->post("{$this->base_url}/transacao", [
                "valor" => $totalValue100, // Vi que a API so aceita integer. Então estou supondo que é um gateway que diz -> R$ 10,59 = 1059
                "nome" => $client->name,
                "email" => $client->email,
                "numeroCartao" => $cardNumber,
                "cvv" => $cvv,
            ]);
        if($response->failed()) return null;
        $external_id = $response->json('id');

        if(!$external_id) return null;
        $transaction = Transaction::create([
            'client' => $client->id,
            'external_id' => $external_id,
            'amount' => $totalValue,
            'gateway' => $this->gateway->id,
            'status' => PaymentStatus::PENDING->value,
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

    public function convertCommonPaymentData(PaymentData $paymentData): CommonPaymentData
    {
        return new CommonPaymentData([
            "id" => $paymentData->id,
            "status" => $this->convertStatus($paymentData->status),
            "amount" => $paymentData->amount
        ]);
    }

    public function convertStatus(string $status): PaymentStatus
    {
        return match ($status) {
            "pending" => PaymentStatus::PENDING,
            "paid" => PaymentStatus::DONE,
            "refused" => PaymentStatus::REFUSED,
            "refunded" => PaymentStatus::REFUNDED,
            "refund_requested" => PaymentStatus::REFUND_REQUESTED,
        };
    }


    public function getPaymentData(string $external_id): ?CommonPaymentData
    {
        $response = Http::withHeaders($this->defaultAuthHeader())
            ->get("{$this->base_url}/transacoes");
        /** @var Collection<PaymentData> $paymentDataList */
        $paymentDataList = collect($response->json('data'))->map(fn($params) => new PaymentData($params));
        /** @var PaymentData $paymentData */
        $paymentData = $paymentDataList->firstWhere('id', $external_id);
        return $this->convertCommonPaymentData($paymentData);
    }

    public function refund(): mixed
    {
        // TODO: Implement refund() method.
    }

    public function listTransactions(): array
    {
        $response = Http::withHeaders($this->defaultAuthHeader())
            ->get("{$this->base_url}/transacoes", [
                "secret" => $this->secret,
                "token" => $this->token,
            ]);
        return $response->json();
    }

    public function login(): void
    {
        // nao precisa
        return;
    }

    public function defaultAuthHeader(): array
    {
        return [
            "Gateway-Auth-Token" => $this->token,
            "Gateway-Auth-Secret" => $this->secret,
        ];
    }
}
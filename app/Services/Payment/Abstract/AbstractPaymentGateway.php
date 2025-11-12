<?php

namespace App\Services\Payment\Abstract;

use App\DTO\ProductDTO;
use App\Models\Client;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class AbstractPaymentGateway
{

    protected string|null $id_token;

    protected string $gt_preffix;

    public function __construct(
        public Gateway $gateway,
        protected string $base_url
    ) {
    }

    /**
     * Process a payment transaction
     *
     * @param Collection<ProductDTO> $productDTOList
     */
    abstract public function transaction(Collection $productDTOList, Client $client, string $cardNumber, string $cvv): ?Transaction;

    /**
     * Process a refund
     *
     * @return mixed
     */
    abstract public function refund(): mixed;

    abstract public function convertStatus(string $status);

    public function checkChangeStatus(Transaction $transaction): void
    {
        //Se ja é o status final, não preciso atualizar
        if(!$transaction->status->isPeddingStatus()) return;
        $commonPaymentData = $this->getPaymentData($transaction->external_id);
        if(!$commonPaymentData) return;
        $transaction->status = $commonPaymentData->status;
        $transaction->save();
    }

    /**
     * @param Collection<ProductDTO> $productListDTO
     */
    protected function calculateValue(Collection $productListDTO) {
        $productListID = $productListDTO->map( fn($productDTO) => $productDTO->id);
        $productList = Product::whereIn('id',$productListID)->get()->keyBy('id');
        $totalValue = $productListDTO->sum(function ($productDTO) use ($productList) {
            /**
             * @var ProductDTO $productDTO
             * @var Product $product
             */
            $product = $productList->get($productDTO->id);
            return $product->amount * $productDTO->quantity;
        });
        return round($totalValue,2);
    }

    abstract public function getPaymentData(string $external_id): ?CommonPaymentData;

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

    public function convertCommonPaymentData($id, $status, $amount ): CommonPaymentData
    {
        return new CommonPaymentData([
            "id" => $id,
            "status" => $this->convertStatus($status),
            "amount" => $amount
        ]);
    }
}
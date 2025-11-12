<?php

namespace App\Services;

use App\DTO\ProductDTO;
use App\DTO\TransactionDTO;
use App\Enums\PaymentStatus;
use App\Models\Client;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\Payment\PaymentGatewayService;
use Illuminate\Support\Collection;

class TransactionService
{
    public function __construct(public PaymentGatewayService $paymentGatewayService){}
    public function list(): Collection
    {
        return Transaction::all();
    }

    public function getTransaction(int $id): ?Transaction
    {
        $transaction = Transaction::find($id);
        if($transaction?->status->isFinalStatus()) {
            $paymentGateway = $this->paymentGatewayService->instantiatePaymentGateway($transaction->gatewayClass);
            $paymentGateway->checkChangeStatus($transaction);
        }
        return $transaction;
    }

    public function beginTransaction(TransactionDTO $transactionDTO) : ?Transaction
    {
        $client = Client::where('email',$transactionDTO->client_email)->get()->first();
        if(!$client) {
            $client = Client::create([
                'name' => $transactionDTO->client_name,
                'email' => $transactionDTO->client_email,
            ]);
        }

        foreach ($this->paymentGatewayService->paymentGatewayList as $paymentGateway) {
            $transaction = $paymentGateway->transaction(
                $transactionDTO->products,
                $client,
                $transactionDTO->card_numbers,
                $transactionDTO->cvv,
            );

            //Se o pagamanto foi concluido ou pendente, terminar o loop
            if(isset($transaction->id) && in_array($transaction->status,[PaymentStatus::DONE, PaymentStatus::PENDING])) break;
        }
        return $transaction;
    }

    public function refund(int $id): ?Transaction
    {
        $transaction = Transaction::find($id);
        if(!$transaction) return null;
        return $this->paymentGatewayService->instantiatePaymentGateway($transaction->gatewayClass)->refund($id);
    }
}
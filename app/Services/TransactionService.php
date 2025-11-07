<?php

namespace App\Services;

use App\Models\Transaction;
use App\Services\Payment\PaymentGatewayService;

class TransactionService
{
    public function __construct(public PaymentGatewayService $paymentGatewayService){}
    public function list() {
        return Transaction::all();
    }
    public function listFromGateway() {
        $transactionList = collect();
        $this->paymentGatewayService->instantiateAll();
        foreach ($this->paymentGatewayService->paymentGatewayList as $paymentGateway) {
            $transactionList->merge([
                $paymentGateway->gateway->alias => $paymentGateway->listTransactions()
            ]);
        }
        return $transactionList;
    }
}
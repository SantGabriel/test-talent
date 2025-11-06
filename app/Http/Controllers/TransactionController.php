<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\Payment\AbstractPaymentGateway;
use App\Services\Payment\PaymentGatewayService;
use Illuminate\Routing\Controller;

class TransactionController extends Controller
{
    public function __construct(public PaymentGatewayService $paymentGatewayService){}
    public function list() {
        $transactionList = Transaction::all();
        foreach ($transactionList as $transaction) {
            /** @var Transaction $transaction */
            $paymentGateway = $this->paymentGatewayService->instantiatePaymentGateway($transaction->gatewayClass);
            $paymentGateway->transaction();
        }
        return response()->json($transactionList);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\Payment\AbstractPaymentGateway;
use App\Services\Payment\PaymentGatewayService;
use App\Services\TransactionService;
use Illuminate\Routing\Controller;

class TransactionController extends Controller
{
    public function __construct(
        private TransactionService $transactionService
    ){}
    public function list() {
        $transactionList = $this->transactionService->list();
        return response()->json($transactionList);
    }
    public function listFromGateway() {
        $transactionList = $this->transactionService->listFromGateway();
        return response()->json($transactionList);
    }
}

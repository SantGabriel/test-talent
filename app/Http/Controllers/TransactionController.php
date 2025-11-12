<?php

namespace App\Http\Controllers;

use App\DTO\TransactionDTO;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Services\Payment\Abstract\CommonPaymentData;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    public function get(Request $request)
    {
        $id = $request->route('id');
        $transaction = $this->transactionService->getTransaction($id);
        if($transaction)
            return response()->json($transaction);
        else
            return response()->json("Transaction do not exists");
    }
    public function beginTransaction(TransactionRequest $request)
    {
        $body = $request->validated();
        $transactionDTO = new TransactionDTO($body);
        $transaction = $this->transactionService->beginTransaction($transactionDTO);
        if($transaction)
            return response()->json([
                'id' => $transaction->id,
                'status' => $transaction->status,
            ]);
        else
            return response()->json("Transaction failed. Try Again later");
    }
}

<?php

namespace App\Services\Payment;

use App\Models\Gateway;

class PaymentGatewayService
{
    /** @var AbstractPaymentGateway[] $paymentGatewayList */
    public array $paymentGatewayList;

    public function __construct()
    {
        $this->instantiateAll();
    }
    public function instantiateAll(): void
    {
        /*** @var Gateway[] $gatewayList */
        $gatewayList = Gateway::where("is_active", true)->get();

        foreach ($gatewayList as $gateway) {
            $paymentGateway = $this->instantiatePaymentGateway($gateway);
            if($paymentGateway) $this->paymentGatewayList[] = $paymentGateway;
        }
    }

    public function instantiatePaymentGateway(Gateway $gateway): ?AbstractPaymentGateway
    {
        $class = "App\\Services\\Payment\\{$gateway->alias}\\PaymentGateway";

        if (!class_exists($class)) return null;

        return new $class($gateway);
    }

    public function generateAllAuthTokens(): void {
        foreach ($this->paymentGatewayList as $paymentGateway) {
            $paymentGateway->login();
        }
    }
    public function startPayment()
    {
        foreach ($this->paymentGatewayList as $paymentGateway) {
            $transaction = $paymentGateway->transaction();
            if($transaction) return $transaction; //transaction funcionou
        }
        return false;
    }
}
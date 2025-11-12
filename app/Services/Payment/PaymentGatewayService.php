<?php

namespace App\Services\Payment;

use App\Models\Gateway;
use App\Services\Payment\Abstract\AbstractPaymentGateway;

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
        $gatewayList = Gateway::where("is_active", true)
            ->orderBy('priority')
            ->orderBy('updated_at', 'desc')
            ->get();

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
}
<?php

namespace App\Services\Payment\Gt2;

use App\Models\Gateway;
use App\Services\Payment\AbstractPaymentGateway;
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
    public function transaction(): mixed
    {
        $response = Http::withHeaders($this->defaultAuthHeader())
            ->get("{$this->base_url}/transacoes", [
                "secret" => $this->secret,
                "token" => $this->token,
            ]);
        return $response->body();
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
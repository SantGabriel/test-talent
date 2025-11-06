<?php

namespace App\Services\Payment\Gt1;

use App\Models\Gateway;
use App\Services\Payment\AbstractPaymentGateway;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PaymentGateway extends AbstractPaymentGateway
{

    private string $email;
    private string $token;

    public function __construct(
        protected Gateway $gateway,
        protected string $base_url = "http://gt:3001"
    )
    {
        parent::__construct($gateway, $base_url);
        $this->gt_preffix = 'GATEWAY_GT1_';
        $this->email = config("gateway.{$this->gt_preffix}EMAIL");
        $this->token = config("gateway.{$this->gt_preffix}TOKEN");
        $this->id_token = Cache::get("{$this->gt_preffix}auth_token");
        $this->login();
    }
    public function transaction(): mixed
    {
        $response = Http::withHeaders($h = $this->defaultAuthHeader())
            ->get("{$this->base_url}/transactions", [
                "email" => $this->email,
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
        // TODO: Implement listTransactions() method.
    }

    public function login(): void
    {
        if($this->id_token) return;
        $http_response = Http::post("{$this->base_url}/login", [
            "email" => $this->email,
            "token" => $this->token,
        ]);
        $response = json_decode($http_response->body());
        $this->id_token = $response->token;
        Cache::put("{$this->gt_preffix}auth_token", $this->id_token, 3600);
    }

    public function defaultAuthHeader(): array
    {
        return [
            'Authorization' => "Bearer {$this->id_token}",
            'Accept' => 'application/json',
        ];
    }
}
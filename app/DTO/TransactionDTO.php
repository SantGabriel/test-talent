<?php

namespace App\DTO;
use Illuminate\Support\Collection;

readonly class TransactionDTO
{
    public string $client_email;
    public string $client_name;

    /** @var $products Collection<ProductDTO> */
    public Collection $products;
    public string $card_numbers;
    public string $cvv;

    public function __construct(array $data)
    {
        $this->client_name = $data['client_name'];
        $this->client_email = $data['client_email'];
        $products = collect($data['products'])->map(fn($product) => new ProductDTO($product));
        $this->products = $products;
        $this->card_numbers = $data['card_numbers'];
        $this->cvv = $data['cvv'];
    }
}
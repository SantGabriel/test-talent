<?php

namespace App\DTO;

readonly class ProductDTO
{
    public int $id;
    public int $quantity;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->quantity = $data['quantity'];
    }
}
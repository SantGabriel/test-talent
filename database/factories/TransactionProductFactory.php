<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransactionProduct>
 */
class TransactionProductFactory extends Factory
{
    protected $model = TransactionProduct::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transaction_id' =>  Transaction::inRandomOrder()->first(),
            'product_id' => Product::inRandomOrder()->first(),
            'quantity' => $this->faker->numberBetween(1, 3),
        ];
    }
}

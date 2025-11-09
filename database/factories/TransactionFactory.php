<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Client;
use App\Models\Gateway;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client' => Client::inRandomOrder()->first(),
            'gateway' => Gateway::inRandomOrder()->first(),
            'external_id' => bin2hex(random_bytes(8)),
            'status' => PaymentStatus::DONE->value,
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'card_last_numbers' => (string) $this->faker->randomNumber(4, true),
        ];
    }
}

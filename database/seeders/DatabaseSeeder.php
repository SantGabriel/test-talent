<?php

namespace Database\Seeders;

use App\Models\TransactionProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GatewaySeeder::class,
            ProductSeeder::class,
            ClientSeeder::class,
            TransactionSeeder::class,
            TransactionProductSeeder::class,
        ]);
    }
}

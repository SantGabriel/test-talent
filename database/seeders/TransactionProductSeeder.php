<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionProduct;
use Database\Factories\TransactionProductFactory;
use Illuminate\Database\Seeder;

class TransactionProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactionList = Transaction::all();
        $productList = Product::all();
        foreach ($transactionList as $transaction ) {
            $nmb_diff_products = random_int(1, 3);
            $productsForThisTransaction = $productList->random($nmb_diff_products);
            foreach ($productsForThisTransaction as $product) {
                $quantity = random_int(1, 3);
                TransactionProduct::factory()->create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Gateway;
use App\Models\Transaction;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gatewayList = Gateway::where('is_active', 1)->get();
        Client::all()->each(function ($client) use ($gatewayList) {
            $gateway = $gatewayList->random();
            Transaction::factory([
                'client' => $client->id,
                'gateway' => $gateway->id,
            ])->create();
        });
    }
}

<?php

namespace Tests\Feature;

use App\DTO\ProductDTO;
use App\DTO\TransactionDTO;
use App\Models\Client;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionProduct;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testBeginTransaction(): Transaction
    {
        $client = Client::factory()->create();
        $qtdProducts = rand(1, 10);
        $productList = Product::factory($qtdProducts)->create();

        $productList = $productList->map(function ($product) {
            $randomQuantity = rand(1, 10);
            return [
                'id' => $product->id,
                'quantity' => $randomQuantity,
            ];
        })->toArray();
        $transactionArrayDTO = [
            "client_email" => $client->email,
            "client_name" => $client->name,
            "products" => $productList,
            "card_numbers" => '1234123412341234',
            "cvv" => '123'
        ];
        $response = $this->post('api/transaction/begin', $transactionArrayDTO, $this->getAuth());

        // Testa se a transação foi feita
        $this->assertNotEquals("Transaction failed. Try Again later", $response->json());

        $idTransaction = $response->json('id');
        $transaction = Transaction::find($idTransaction);
        $transactionProductList = $transaction->transactionProducts;

        //Testa se salvou o cliente e os produtos
        $this->assertEquals($client->id, $transaction->client);
        $this->assertEquals(sizeof($transactionProductList), $qtdProducts);

        $sum = 0;
        foreach ($transactionProductList as $transactionProduct) {
            $sum += $transactionProduct->product->amount * $transactionProduct->quantity;
        }
        $sum = round($sum,2);

        // Testa se somou tudo certo
        $this->assertEquals($sum, $transaction->amount);
        $response->assertStatus(Response::HTTP_OK);
        return $transaction;
    }

    public function testGt1() {

        Gateway::where('alias', 'Gt1')->update([
            'is_active' => true,
            'priority' => 0
        ]);
        $transaction = $this->testBeginTransaction();

        $gatewayPrioritario = Gateway::where('is_active', true)->orderBy('priority')->firstOrFail();
        $this->assertEquals($gatewayPrioritario, $transaction->gatewayClass);

    }

    public function testGt2() {
        Gateway::where('alias', 'Gt1')->update([
            'is_active' => true,
            'priority' => 0
        ]);
        $transaction = $this->testBeginTransaction();

        $gatewayPrioritario = Gateway::where('is_active', true)->orderBy('priority')->firstOrFail();
        $this->assertEquals($gatewayPrioritario->toArray(), $transaction->gatewayClass->toArray());
    }

    public function testTransactionFakeGatewayHighestPriority()
    {
        Gateway::where('alias', 'GtFake')->update([
            'is_active' => true,
            'priority' => 1
        ]);
        $gatewayPrioritario = Gateway::where('alias', 'Gt2')->firstOrFail();
        $gatewayPrioritario->update([
            'is_active' => true,
            'priority' => 2
        ]);
        Gateway::where('alias', 'Gt1')->update([
            'is_active' => true,
            'priority' => 3
        ]);

        $transaction = $this->testBeginTransaction();

        $this->assertEquals($gatewayPrioritario->toArray(), $transaction->gatewayClass->toArray());
    }

    public function testTransactionDeactiveGateway()
    {
        Gateway::where('alias', 'Gt1')->update([
            'is_active' => false,
            'priority' => 2
        ]);
        $gatewayPrioritario = Gateway::where('alias', 'Gt2')->firstOrFail();
        $gatewayPrioritario->update([
            'is_active' => true,
            'priority' => 3
        ]);

        $transaction = $this->testBeginTransaction();

        $this->assertEquals($gatewayPrioritario->toArray(), $transaction->gatewayClass->toArray());
    }
}

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
    public function testBeginTransaction(): void
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

        // Testa se a transaçaõ foi feita
        $this->assertNotEquals("Transaction failed. Try Again later", $response->json());

        $idTransaction = $response->json('id');
        $transaction = Transaction::find($idTransaction);
        $transactionProductList = $transaction->transactionProducts;

        //Testa se salvou o cliente e os produtos
        $this->assertEquals($client->id, $transaction->client);
        $this->assertEquals(sizeof($transactionProductList), $qtdProducts);

        //Testa se usou o gateway mais prioritario
        $gatewayPrioritario = Gateway::where('is_active', true)->orderBy('priority')->first();
        $this->assertEquals($gatewayPrioritario, $transaction->gatewayClass);

        $sum = 0;
        foreach ($transactionProductList as $transactionProduct) {
            $sum += $transactionProduct->product->amount * $transactionProduct->quantity;
        }
        $sum = round($sum,2);

        // Testa se somou tudo certo
        $this->assertEquals($sum, $transaction->amount);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testTransactionFakeGatewayHighestPriority()
    {
        
    }

    public function testTransactionDeactiveGateway()
    {

    }
}

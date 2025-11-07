<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductTest extends TestCase
{

    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreate(): void
    {
        $body = [
            "name" => "Beterraba",
            "amount" => 7.50,
        ];

        $response = $this->post('api/product/', $body, $this->getAuth());
        $product = Product::latest('id')->first();
        $this->assertEquals($product->name, $body['name']);
        $this->assertEquals($product->amount, $body['amount']);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testRead(): void
    {
        $product = Product::factory([
            "name" => "Abacaxi",
            "amount" => 10,
        ])->create();
        $response = $this->get('api/product/' . $product->id, $this->getAuth());
        $this->assertEquals($product->name, $response['name']);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testUpdate(): void
    {
        $productOriginal = Product::factory([
            "name" => "Amora",
            "amount" => 20.05,
        ])->create();
        $body = [
            "amount" => 50,
        ];
        $response = $this->put('api/product/' . $productOriginal->id, $body, $this->getAuth());
        $productUpdated = Product::find($productOriginal->id);
        $this->assertEquals($productUpdated->amount, $body['amount']);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testDelete(): void
    {
        $productOriginal = Product::factory([
            "name" => "Abacate",
            "amount" => 15,
        ])->create();
        $this->delete("api/product/{$productOriginal->id}", [], $this->getAuth());
        $productDeleted = Product::where('id', $productOriginal->id)
            ->first();
        $this->assertEquals(false, $productDeleted->active);
    }
}

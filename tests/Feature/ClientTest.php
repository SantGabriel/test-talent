<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function testClientList(): void
    {
        $response = $this->get('api/client', $this->getAuth());

        $clients = Client::all(["name"]);
        $response->assertJson($clients->toArray());
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testClientData()
    {
        $client = Client::factory()->create();
        Transaction::factory(3)->create([
            'client' => $client->id,
        ]);
        $response = $this->get("api/client/{$client->id}", $this->getAuth());
        $response->assertJson($client->toArray());
    }

    public function testClientNotFound()
    {
        $response = $this->get("api/client/0", $this->getAuth());
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}

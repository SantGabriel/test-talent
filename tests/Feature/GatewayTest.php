<?php

namespace Tests\Feature;

use App\Models\Gateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class GatewayTest extends TestCase
{

    use RefreshDatabase;
    public function testGatewayChangeStatus()
    {
        /** @var $gtWillBeTurnedOff Gateway */
        $gtWillBeTurnedOff = Gateway::factory()->create(['is_active' => true]);
        $response = $this->post("api/gateway/activate/{$gtWillBeTurnedOff->id}", ['is_active' => false], $this->getAuth());
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertFalse($gtWillBeTurnedOff->refresh()->is_active);

        /** @var $gtWillBeTurnedOn Gateway */
        $gtWillBeTurnedOn = Gateway::factory()->create(['is_active' => false]);
        $response = $this->post("api/gateway/activate/{$gtWillBeTurnedOn->id}", ['is_active' => true], $this->getAuth());
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertTrue($gtWillBeTurnedOn->refresh()->is_active);
    }

    public function testGatewayChangePriority()
    {
        $gt = Gateway::factory()->create();
        $randomPriority = rand(1, 10);
        $response = $this->post("api/gateway/priority/{$gt->id}", ["priority" => $randomPriority], $this->getAuth() );

        $gatewayAfterChangePriority = Gateway::find($gt->id);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals($randomPriority, $gatewayAfterChangePriority->priority);
    }

    public function testGatewayNotFound()
    {
        $response = $this->post("api/gateway/activate/0", ['is_active' => true], $this->getAuth());
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}

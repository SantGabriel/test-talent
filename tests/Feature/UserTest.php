<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Gateway;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{

    use RefreshDatabase;
    public function testCreate(): void
    {
        $body = [
            "email" => "outroadmin@hotmail.com",
            "password" => "lerolero",
            "role" => Role::ADMIN->value,
        ];

        $response = $this->post('api/user/', $body, $this->getAuth());
        $user = User::latest('id')->first();

        $checkPass = Hash::check($body['password'], $user->password);
        $this->assertTrue($checkPass);
        $this->assertEquals(Role::ADMIN,$user->role);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testRead(): void
    {
        $user = User::factory([
            "email" => "financeiro@hotmail.com",
            "password" => "senhaSegura",
            "role" => Role::FINANCE->value,
        ])->create();
        $response = $this->get('api/user/' . $user->id, $this->getAuth());

        $response->assertJson([
            "id" => $user->id,
            "email" => $user->email,
            "role" => $user->role->value,
        ]);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testUpdate(): void
    {
        $userOriginal = User::factory([
            "email" => "manager@hotmail.com",
            "password" => "password",
            "role" => Role::MANAGER,
        ])->create();
        $body = [
            "email" => "outroEmail@hotmail.com",
            "password" => "outraSenha",
            "role" => Role::ADMIN->value,
        ];
        $response = $this->put('api/user/' . $userOriginal->id, $body, $this->getAuth());
        $userUpdated = User::find($userOriginal->id);

        $this->assertEquals($userUpdated->email, $body['email']);

        $checkPass = Hash::check($body['password'], $userUpdated->password);
        $this->assertTrue($checkPass);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testDelete(): void
    {
        $user = User::first();
        $this->delete("api/user/{$user->id}", [], $this->getAuth());
        $this->assertDatabaseMissing('users', $user->toArray());
    }

    public function testUserNotFound() {
        $response = $this->get('api/user/0', $this->getAuth());

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testRolesRoute() {
        // Admin
        $token = $this->generateToken(Role::ADMIN);
        $gt = Gateway::factory()->create();
        $response = $this->post("api/gateway/activate/{$gt->id}", ['is_active' => true] ,$this->getAuth($token));
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        // Manager
        $token = $this->generateToken(Role::MANAGER);
        $response = $this->post('api/gateway/activate/1', ['is_active' => true], $this->getAuth($token));
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        // Finance
        $token = $this->generateToken(Role::FINANCE);
        $response = $this->get('api/client/1', $this->getAuth($token));
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        // User
        $token = $this->generateToken(Role::USER);
        $response = $this->get('api/user/1', $this->getAuth($token));
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        //No Auth
        $response = $this->get('api/transaction/refund/1');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}

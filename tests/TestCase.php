<?php

namespace Tests;

use App\Enums\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected string $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->token = $this->generateToken();
    }

    protected function getAuth(?string $token = null): array
    {
        if (is_null($token)) $token = $this->token;
        return [
            'Authorization' => 'Bearer ' . $token
        ];
    }

    protected function generateToken(Role $role = Role::ADMIN, $password = "password"): string {
        $userService = app(UserService::class);
        $user = User::where('role',$role)->first();
        return $userService->generateLoginToken($user->email, $password);
    }
}

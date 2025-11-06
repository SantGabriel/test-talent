<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    public function generateLoginToken(string $email, string $password) : ?string
    {
        $user = User::where('email', $email)->first();

        if(!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);
        return $token;
//        JWTAuth::claims(['role' => ])->fromUser($user);
//        return view('login');
    }

    public function create(array $params): int {
        $params['password'] = Hash::make($params['password']);
        return User::create($params)->id;
    }

    public function update(int $id, array $params): bool {
        $params['password'] = Hash::make($params['password']);
        return User::find($id)?->update($params);
    }

    public function delete(int $id): bool {
        return User::find($id)?->delete();
    }

    public function read(int $id): ?User {
        return User::find($id);
    }
}
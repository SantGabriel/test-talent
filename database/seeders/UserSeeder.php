<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Role::cases() as $role) {
            if ($role == Role::ADMIN) {
                User::factory()->create([
                    'role' => $role,
                    'email' => "admin@gmail.com"
                ]);
            }
            User::factory()->create([
                'role' => $role
            ]);
        }
    }
}

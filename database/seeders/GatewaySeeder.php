<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gateway;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gateway::create([
            'name' => 'Gt1',
            'is_active' => true,
            'priority' => 1,
        ]);

        Gateway::create([
            'name' => 'Gt2',
            'is_active' => true,
            'priority' => 2,
        ]);

        Gateway::create([
            'name' => 'Gt Fake',
            'is_active' => false,
            'priority' => 3,
        ]);
    }
}

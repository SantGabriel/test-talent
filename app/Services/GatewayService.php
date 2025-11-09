<?php

namespace App\Services;

use App\Models\Gateway;

class GatewayService
{

    public function activate(int $id, bool $activate) {
        return Gateway::find($id)?->update(['is_active' => $activate]);
    }

    public function priority(int $id, int $priority) {
        return Gateway::find($id)?->update(['priority' => $priority]);
    }
}
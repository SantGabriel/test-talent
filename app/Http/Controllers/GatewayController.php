<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GatewayController
{
    public function activate() {
        return response()->json('ok');
    }
}

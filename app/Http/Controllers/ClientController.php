<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ClientController extends Controller
{
    public function __construct()
    {
    }

    public function index(): JsonResponse
    {
        $clients = Client::all();

        return response()->json($clients);
    }

    public function show(Request $request): JsonResponse
    {
        $id = $request->route('id',0);
        return response()->json(Client::find($id));
    }
}

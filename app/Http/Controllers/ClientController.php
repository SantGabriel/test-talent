<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ClientController extends Controller
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function list(): JsonResponse
    {
        return response()->json($this->clientService->list());
    }

    public function getById(Request $request): JsonResponse
    {
        $id = $request->route('id',0);
        $client = $this->clientService->getById($id);
        if($client)
            return response()->json($client);
        else
            return response()->json("Not found",Response::HTTP_NOT_FOUND);
    }
}

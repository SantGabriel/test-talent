<?php

namespace App\Http\Controllers;

use App\Services\GatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GatewayController
{
    public function __construct(private GatewayService $gatewayService) {}
    public function activate(Request $request): JsonResponse
    {
        $id = $request->route('id');
        $activate = $request->post('is_active');
        $reponse = $this->gatewayService->activate($id, $activate);
        if($reponse)
            return response()->json('',Response::HTTP_NO_CONTENT);
        else
            return response()->json(['error' => 'Falha'], Response::HTTP_NOT_FOUND);
    }
    public function priority(Request $request)
    {
        $id = $request->route('id');
        $priority = $request->post('priority');
        $reponse = $this->gatewayService->priority($id, $priority);
        if($reponse)
            return response()->json('',Response::HTTP_NO_CONTENT);
        else
            return response()->json(['error' => 'Falha'], Response::HTTP_NOT_FOUND);
    }
}

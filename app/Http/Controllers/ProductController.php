<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService) {}

    public function create(Request $request): JsonResponse {
        $this->productService->create($request->post());
        return response()->json('',Response::HTTP_NO_CONTENT);
    }

    public function update(Request $request): JsonResponse {
        $id = $request->route('id');
        $body = $request->post();
        $response = $this->productService->update($id, $body);

        if($response)
            return response()->json('',Response::HTTP_NO_CONTENT);
        else
            return response()->json(['error' => 'Falha'], Response::HTTP_BAD_REQUEST);
    }

    public function delete(Request $request): JsonResponse {
        $id = $request->route('id');
        $response = $this->productService->delete($id);
        if($response)
            return response()->json('',Response::HTTP_NO_CONTENT);
        else
            return response()->json(['error' => 'Falha'], Response::HTTP_BAD_REQUEST);
    }

    public function read(Request $request): JsonResponse {
        $id = $request->route('id');
        $product = $this->productService->read($id);
        if($product)
            return response()->json($product,Response::HTTP_OK);
        else
            return response()->json(['error' => 'Falha'], Response::HTTP_BAD_REQUEST);
    }
}

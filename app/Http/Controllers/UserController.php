<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function __construct(public UserService $userService) {}
    public function login(Request $request): JsonResponse
    {
        $email = $request->post('email');
        $password = $request->post('password');

        $token = $this->userService->generateLoginToken($email, $password);

        if($token) {
            return response()->json(['token' => $token]);
        }else {
            return response()->json(['error' => 'Credenciais inválidas'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function create(Request $request): JsonResponse {
        $this->userService->create($request->post());
        return response()->json('',Response::HTTP_NO_CONTENT);
    }

    public function update(Request $request): JsonResponse {
        $id = $request->route('id');
        $body = $request->post();
        $response = $this->userService->update($id, $body);

        if($response)
            return response()->json('',Response::HTTP_NO_CONTENT);
        else
            return response()->json(['error' => 'Falha'], Response::HTTP_BAD_REQUEST);
    }

    public function delete(Request $request): JsonResponse {
        $id = $request->route('id');
        $response = $this->userService->delete($id);

        if($response)
            return response()->json('',Response::HTTP_NO_CONTENT);
        else
            return response()->json(['error' => 'Falha'], Response::HTTP_BAD_REQUEST);
    }

    public function read(Request $request): JsonResponse {
        $id = $request->route('id');
        $user = $this->userService->read($id);
        $d = $user->toArray();
        if($user)
            return response()->json($user->toArray(),Response::HTTP_OK);
        else
            return response()->json(['error' => 'Falha'], Response::HTTP_BAD_REQUEST);
    }
}

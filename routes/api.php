<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

/************ User ************/

Route::prefix('user')->group(function () {
    Route::post('/login', [UserController::class, 'login']);

    Route::middleware(['auth:manager'])->group(function () {
        Route::post('/', [UserController::class, 'create']);
        Route::get('/{id}', [UserController::class, 'read']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete']);
    });
});

/************ Transaction ************/
Route::prefix('transaction')->group(function () {
    //Realizar uma compra informando o produto
    Route::post('/begin', [TransactionController::class, 'beginTransaction']);

    Route::middleware(['auth:user'])->group(function () {
        // Listar todas as compras
        Route::get('/list', [TransactionController::class, 'list']);
        // Listar todas as compras
        Route::get('/listFromGateway', [TransactionController::class, 'listFromGateway']);

        // Detalhes de uma compra
        Route::get('/get/{id}', [TransactionController::class, 'get']);
    });

    Route::middleware(['auth:finance'])->group(function () {
        // Realizar reembolso de uma compra junto ao gateway com validação por roles
        Route::post('/refund/{id}', [TransactionController::class, 'refund']);
    });
});

/************ Gateway ************/
Route::prefix('gateway')->middleware(['auth:user'])->group(function () {
    // Ativar/desativar um gateway
    Route::post('/activate/{id}', [GatewayController::class, 'activate']);

    // Alterar a prioridade de um gateway
    Route::post('/priority/{id}', [GatewayController::class, 'priority']);
});

/************ Product ************/
Route::prefix('product')->middleware(['auth:manager,finance'])->group(function () {
    // CRUD de produtos com validação por roles
    Route::get('/{id}', [ProductController::class, 'read']);
    Route::post('/', [ProductController::class, 'create']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::delete('/{id}', [ProductController::class, 'delete']);
});

/************ Client ************/
Route::prefix('client')->middleware(['auth:manager'])->group(function () {
    // Listar todos os clientes
    Route::get('/', [ClientController::class, 'list']);

    // Detalhe do cliente e todas suas compras
    Route::get('/{id}', [ClientController::class, 'getById']);
});



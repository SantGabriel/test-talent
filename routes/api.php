<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;

/************ User ************/
Route::prefix('user')->group(function () {
    //Realizar o login
    Route::post('/login', [UserController::class, 'login']);

    //CRUD de usuários com validação por roles
    Route::get('/', [UserController::class, 'read']);
    Route::post('/', [UserController::class, 'create']);
    Route::put('/', [UserController::class, 'update']);
    Route::delete('/', [UserController::class, 'delete']);
});

/************ Transaction ************/
Route::prefix('transaction')->group(function () {
    //Realizar uma compra informando o produto
    Route::post('/begin', [TransactionController::class, 'new']);

    // Listar todas as compras
    Route::get('/list', [TransactionController::class, 'list']);

    // Detalhes de uma compra
    Route::get('/get/{id}', [TransactionController::class, 'get']);

    // Realizar reembolso de uma compra junto ao gateway com validação por roles
    Route::post('/refund/id', [TransactionController::class, 'refund']);
});

/************ Gateway ************/
Route::prefix('gateway')->group(function () {
    // Ativar/desativar um gateway
    Route::get('/active/{id}', [GatewayController::class, 'active']);

    // Alterar a prioridade de um gateway
    Route::post('/priority/{id}', [GatewayController::class, 'priority']);
});

/************ Product ************/
Route::prefix('product')->group(function () {
    // CRUD de produtos com validação por roles
    Route::get('/', [ProductController::class, 'read']);
    Route::post('/', [ProductController::class, 'create']);
    Route::put('/', [ProductController::class, 'update']);
    Route::delete('/', [ProductController::class, 'delete']);
});

/************ Client ************/
Route::prefix('client')->group(function () {
    // Listar todos os clientes
    Route::get('/', [ClientController::class, 'index']);

    // Detalhe do cliente e todas suas compras
    Route::get('/{id}', [ClientController::class, 'show']);
});



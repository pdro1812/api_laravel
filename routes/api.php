<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListUsersController;
use App\Http\Controllers\FinanceController;

Route::get('/', function () {
    return "view('welcome')";
});


//criar uma rota para salvar um usuário
Route::post('/save_user', [UserController::class, 'store']);

//criar uma rota para listar todos os usuários
Route::get('/list_users', [ListUsersController::class, 'index']);

//criar uma rota para mostrar um usuário específico
Route::get('/list_user/{id}', [ListUsersController::class, 'show']);

//criar uma rota para excluir um usuário específico
Route::get('/delete_user/{id}', [ListUsersController::class, 'destroy']);


Route::prefix('finance')->group(function () {
    Route::get('/list_user_transactions/{id}', [FinanceController::class, 'listUserTransactions']);
    Route::get('/list_transactions', [FinanceController::class, 'listTransactions']);
    Route::get('/remove_transaction/{id}', [FinanceController::class, 'removeTransaction']);
    Route::get('/{id}/balance', [FinanceController::class, 'balance']);
    Route::post('/{id}/credit/{valor}', [FinanceController::class, 'addBalance']);
    Route::post('/{id}/debit/{valor}', [FinanceController::class, 'removeBalance']);
    Route::get('/generateCSVumMes', [FinanceController::class, 'generateCSVumMes']);
    Route::get('/generateCSVmes/{mes}/{ano}', [FinanceController::class, 'generateCSVmes']);
    Route::get('/generateCSVall', [FinanceController::class, 'generateCSVall']);

});
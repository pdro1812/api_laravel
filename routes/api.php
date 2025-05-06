<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListUsersController;

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

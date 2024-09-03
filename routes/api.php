<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LogoutController;
use App\Http\Controllers\Api\UserController;


// Mendefinisikan rute API :login dan logout
Route::post('/login', [LoginController::class, '__invoke'])->name('login');
Route::post('/logout', [LogoutController::class, '__invoke'])->name('logout');

// Rute API yang memerlukan autentikasi
Route::middleware('auth:api')->get('/datauser', function (Request $request) {
    return $request->user();
});

// Mendefinisikan rute API untuk menu User
Route::get('/user', [UserController::class, 'index']);
Route::post('/user/store', [UserController::class, 'store']);
Route::get('/user/show/{id}', [UserController::class, 'show']);
Route::patch('/user/update/{id}', [UserController::class, 'update']);
Route::delete('/user/destroy/{id}', [UserController::class, 'delete']);
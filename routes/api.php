<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\LevelController;

Route::pattern('id', '[0-9]+'); // arti: ketika ada parameter {id}, maka harus berupa angka
Route::post('/register', App\Http\Controllers\Api\RegisterController::class)->name('register');
Route::post('/login', App\Http\Controllers\Api\LoginController::class)->name('name');
Route::middleware('auth:api')->get('/user', function(Request $request){
    return $request->user();
});
Route::post('/logout', App\Http\Controllers\Api\LogoutController::class)->name('logout');

// Route Level
Route::get('levels', [LevelController::class, 'index']);
Route::post('levels', [LevelController::class, 'store']);
Route::get('levels/{id}', [LevelController::class, 'show']);
Route::put('levels/{id}', [LevelController::class, 'update']);
Route::delete('levels/{id}', [LevelController::class, 'destroy']);

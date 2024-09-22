<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

// Protected route
Route::middleware('auth:sanctum')->post('logout', [UserController::class, 'logout']);
Route::middleware('auth:sanctum')->get('user', [UserController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('user/{id}', [UserController::class, 'updateUser']);
Route::middleware('auth:sanctum')->delete('user', [UserController::class, 'deleteUser']);



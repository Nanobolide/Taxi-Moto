<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Ici sont définies les routes de l’API, protégées ou non.
|
*/



Route::get('/test', function () {
    return response()->json(['message' => 'API OK']);
});
  

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

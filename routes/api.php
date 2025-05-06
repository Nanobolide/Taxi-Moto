<?php

use App\Http\Controllers\LocationController;
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

// Route pour mettre à jour la position de l'utilisateur
Route::middleware('auth:sanctum')->put('/location', [LocationController::class, 'update']);

// Route pour récupérer les conducteurs à proximité
Route::middleware('auth:sanctum')->get('/drivers-nearby', [LocationController::class, 'nearbyDrivers']);
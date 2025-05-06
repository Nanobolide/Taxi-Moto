<?php

use App\Http\Controllers\CourseController;
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


// Route pour créer une course

Route::middleware('auth:sanctum')->post('/courses', [CourseController::class, 'create']); // Créer une course (passager)
Route::middleware('auth:sanctum')->put('/courses/{id}/accept', [CourseController::class, 'accept']); // Accepter une course (conducteur)
Route::middleware('auth:sanctum')->put('/courses/{id}/refuse', [CourseController::class, 'refuse']); // Refuser une course (conducteur)
Route::middleware('auth:sanctum')->put('/courses/{id}/start', [CourseController::class, 'start']); // Démarrer une course (conducteur)
Route::middleware('auth:sanctum')->put('/courses/{id}/finish', [CourseController::class, 'finish']); // Terminer une course (conducteur)

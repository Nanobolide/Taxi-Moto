<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    // Met à jour la position du passager (latitude, longitude)
    public function update(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Récupérer l'utilisateur authentifié
        $user = $request->user();

        // Mettre à jour les coordonnées géographiques
        $user->latitude = $request->latitude;
        $user->longitude = $request->longitude;
        $user->save();

        return response()->json([
            'message' => 'Position mise à jour avec succès',
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
        ]);
    }

    // Retourne les conducteurs à proximité (dans un rayon de 5 km)
    public function nearbyDrivers(Request $request)
{
    $radius = 5;

    $user = $request->user();

    if (!$user->latitude || !$user->longitude) {
        return response()->json(['message' => 'Position non définie'], 400);
    }

    $latitude = $user->latitude;
    $longitude = $user->longitude;

    $drivers = User::selectRaw("*, 
    (6371 * acos(
        cos(radians(?)) 
        * cos(radians(latitude)) 
        * cos(radians(longitude) - radians(?)) 
        + sin(radians(?)) * sin(radians(latitude))
    )) AS distance", [$latitude, $longitude, $latitude])
    ->where('role', 'conducteur')
    ->havingRaw("distance <= ?", [$radius])
    ->orderBy('distance')
    ->get();


    if ($drivers->isEmpty()) {
        return response()->json(['message' => 'Aucun conducteur à proximité'], 404);
    }

    return response()->json($drivers);
}

}

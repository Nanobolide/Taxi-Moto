<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use App\Utilis\GeoUtils;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Créer une course (pour le passager)
    public function create(Request $request)
    {
        $request->validate([
            'depart' => 'required|string',
            'destination' => 'required|string',
        ]);
    
        $course = Course::create([
            'passager_id' => auth()->id(), // L'ID du passager connecté
            'depart' => $request->depart,
            'destination' => $request->destination,
            'statut' => 'en attente',
        ]);
    
        return response()->json($course, 201);
    }

    // Accepter ou refuser la course (pour le conducteur)
    public function accept($id)
    {
        $course = Course::findOrFail($id);
    
        // Vérifier si la course est déjà acceptée
        if ($course->statut == 'acceptée') {
            return response()->json(['message' => 'Cette course est déjà acceptée'], 400);
        }
    
        // Mettre à jour le statut de la course à "acceptée" et enregistrer l'heure de départ
        $course->statut = 'acceptée';
        $course->depart_time = now();
        $course->save();
    
        return response()->json([
            'message' => 'Course acceptée avec succès.',
            'course' => $course
        ]);
    }

    // Refuser une course (pour le conducteur)
    public function refuse($id)
    {
        $course = Course::findOrFail($id);
    
        if ($course->statut !== 'en attente') {
            return response()->json(['message' => 'Cette course ne peut plus être refusée'], 400);
        }
    
        $course->statut = 'refusée';
        $course->save();
    
        return response()->json($course);
    }

    // Commencer la course (pour le conducteur)
    public function start($id)
    {
        $course = Course::findOrFail($id);
    
        if ($course->statut !== 'acceptée') {
            return response()->json(['message' => 'Cette course ne peut pas commencer'], 400);
        }
    
        $course->statut = 'en cours';
        $course->depart_time = now(); // Enregistre l'heure de départ
        $course->save();
    
        // Estimation si les coordonnées sont présentes
        if ($course->latitude_depart && $course->longitude_depart && $course->latitude_arrivee && $course->longitude_arrivee) {
            $distanceKm = GeoUtils::haversine_distance(
                $course->latitude_depart,
                $course->longitude_depart,
                $course->latitude_arrivee,
                $course->longitude_arrivee
            );
    
            // Utilisation de la méthode estimer_duree pour calculer la durée estimée
            $dureeMinutes = GeoUtils::estimer_duree(
                $course->latitude_depart,
                $course->longitude_depart,
                $course->latitude_arrivee,
                $course->longitude_arrivee
            );
    
            $arriveeEstimee = Carbon::parse($course->depart_time)->addMinutes($dureeMinutes);
        } else {
            $distanceKm = 0;
            $dureeMinutes = 0;
            $arriveeEstimee = null;
        }
    
        return response()->json([
            'course' => $course,
            'distance_km' => round($distanceKm, 2),
            'duree_estimee_minutes' => round($dureeMinutes),
            'arrivee_estimee' => $arriveeEstimee ? $arriveeEstimee->format('H:i') : 'Non disponible',
        ]);
    }

    // Terminer la course (pour le conducteur)
    public function finish($id)
    {
        $course = Course::findOrFail($id);
    
        if ($course->statut !== 'en cours') {
            return response()->json(['message' => 'Cette course ne peut pas être terminée'], 400);
        }
    
        $course->statut = 'terminée';
        $course->save();
    
        return response()->json($course);
    }

    // Mise à jour de la position de la course
    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $course = Course::findOrFail($id);

        // On met à jour la position du conducteur ou passager
        if ($request->has('latitude') && $request->has('longitude')) {
            $course->latitude_arrivee = $request->latitude;
            $course->longitude_arrivee = $request->longitude;
            $course->save();
        }

        return response()->json($course);
    }

    // Ajouter un incident
    public function addIncident(Request $request, $id)
    {
        $request->validate([
            'incident' => 'required|string',
        ]);
    
        $course = Course::findOrFail($id);
    
        $course->incidents = $course->incidents ? $course->incidents . "\n" . $request->incident : $request->incident;
        $course->save();
    
        return response()->json($course);
    }

    // Historique des courses (pour le passager et le conducteur)
    public function history(Request $request)
    {
        $user = $request->user(); // L'utilisateur connecté

        // Si l'utilisateur est un passager, on récupère ses courses
        if ($user->role == 'passager') {
            $courses = Course::where('passager_id', $user->id)->get();
        }
        // Si l'utilisateur est un conducteur, on récupère ses courses
        elseif ($user->role == 'conducteur') {
            $courses = Course::where('conducteur_id', $user->id)->get();
        }

        return response()->json($courses);
    }


    //Review
    public function addReview(Request $request, $id)
    {
        $validated = $request->validate([
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:255',
        ]);
    
        // Trouver la course
        $course = Course::find($id);
    
        // Vérifier si la course existe et si le passager est bien celui qui effectue la demande
        if (!$course || $course->passager_id !== $request->user()->id) {
            return response()->json(['message' => 'Course not found or you are not the passenger'], 404);
        }
    
        // Vérifier si un conducteur est bien associé à la course
        if (!$course->conducteur_id) {
            return response()->json(['message' => 'No conductor assigned to this course'], 400);
        }
    
        // Créer la critique
        $review = Review::create([
            'passager_id' => $request->user()->id,
            'conducteur_id' => $course->conducteur_id,
            'note' => $validated['note'],
            'commentaire' => $validated['commentaire'],
        ]);
    
        return response()->json($review, 201);
    }
    
    
    
}

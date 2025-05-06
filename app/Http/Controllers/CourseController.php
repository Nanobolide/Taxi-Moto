<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //Créer une course (pour le passager
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

    // b. Accepter ou refuser la course (pour le conducteur)
    public function accept($id)
    {
        $course = Course::findOrFail($id);
    
        if ($course->statut !== 'en attente') {
            return response()->json(['message' => 'Cette course ne peut plus être acceptée'], 400);
        }
    
        $course->conducteur_id = auth()->id();
        $course->statut = 'acceptée';
        $course->save();
    
        return response()->json($course);
    }
    
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
    // c. Commencer et Terminer la course (pour le conducteur)    

    public function start($id)
    {
        $course = Course::findOrFail($id);
    
        if ($course->statut !== 'acceptée') {
            return response()->json(['message' => 'Cette course ne peut pas commencer'], 400);
        }
    
        $course->statut = 'en cours';
        $course->save();
    
        return response()->json($course);
    }
    
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
    

    
}

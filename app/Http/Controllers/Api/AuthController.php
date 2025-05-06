<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 🔐 Inscription
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:passager,conducteur',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inscription réussie',
            'user' => $user,
            'token' => $token,
        ]);
    }

    // 🔐 Connexion
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

       // Tentative de connexion avec les identifiants
    if (!Auth::attempt($credentials)) {
        return response()->json(['message' => 'Identifiants invalides'], 401);
    }

    // Récupérer l'utilisateur
    $user = User::where('email', $request->email)->first();

    // Générer le token
    return response()->json([
        'token' => $user->createToken('mobile')->plainTextToken,
        'user' => $user,
    ]);
    }

    // 🔐 Déconnexion
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }
}

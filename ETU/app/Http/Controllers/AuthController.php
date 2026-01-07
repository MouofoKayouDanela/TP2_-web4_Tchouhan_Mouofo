<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
     /**
 * @OA\Post(
 *     path="/api/signup",
 *     summary="Inscription utilisateur",
 *     description="Enregistrer un nouvel utilisateur.  Limite : 5 requêtes par minute.",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first_name","last_name","login","email","password","password_confirmation"},
 *             @OA\Property(property="first_name", type="string", example="John"),
 *             @OA\Property(property="last_name", type="string", example="Doe"),
 *             @OA\Property(property="login", type="string", example="jdoe"),
 *             @OA\Property(property="email", type="string", example="john@test.com"),
 *             @OA\Property(property="password", type="string", example="password123"),
 *            @OA\Property(property="password_confirmation", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(response=201, description="Utilisateur créé"),
 *     @OA\Response(response=422, description="Erreur de validation"),
 *     @OA\Response(response=429, description="Trop de requêtes")
 * )
 */
    public function register(RegisterRequest $request)
    {
         $validated = $request->validated();
                    
            $user = User::create([
                    'login' => $validated['login'],
                    'password' => bcrypt($validated['password']),
                    'email' => $validated['email'],
                    'last_name' => $validated['last_name'] ,
                    'first_name' => $validated['first_name'],
                ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token                
            ], CREATED);
    }
/**
 * @OA\Post(
 *     path="/api/signin",
 *     summary="Connexion utilisateur",
 *     description="Connecte l'utilisateur.  Limite : 5 requêtes par minute.",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","login"},
 *             @OA\Property(property="login", type="string", example="jdoe"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(response=200, description="Connexion réussie"),
 *     @OA\Response(response=401, description="Identifiants invalides"),
 *     @OA\Response(response=429, description="Trop de requêtes")
 * )
 */
    public function login(LoginRequest $request)
    {
         $validated = $request->validated();           

            if (!Auth::attempt($validated)) {
                throw ValidationException::withMessages([
                    'login' => ['Nom utilisateur ou mot de passe incorrect.'],
                ]);
            }

            $user = Auth::user();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'auth_token' => $token
            ], OK);  
    }
/**
 * @OA\Post(
 *     path="/api/signout",
 *     summary="Déconnexion utilisateur",
 *     description="Déconnecte l'utilisateur authentifié.  Limite : 5 requêtes par minute.",
 *     tags={"Auth"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(response=200, description="Déconnecté"),
 *     @OA\Response(response=401, description="Non authentifié")
 * )
 */
  public function logout(Request $request)
    {
       
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ], OK);
    }


  
}



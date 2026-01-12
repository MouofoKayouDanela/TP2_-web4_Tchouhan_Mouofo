<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{
    
    /**
     * Store a newly created resource in storage.
     */


    /**
 * @OA\Post(
 *     path="/register",
 *     summary="Enregistrer un nouvel utilisateur",
 *          tags={"Authentication"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"login", "email", "password"},
 *             @OA\Property(property="login", type="string", example="paulpol"),
 *             @OA\Property(property="first_name", type="string", example="Paul"),
 *             @OA\Property(property="last_name", type="string", example="Pol"),
 *             @OA\Property(property="email", type="string", example="paul@gig.com"),
 *             @OA\Property(property="password", type="string", example="password123"),
 *            @OA\Property(property="role_id", type="integer", example=2)
 *         )
 *     ),
 *    @OA\Response(
 *         response=201,
 *         description="Utilisateur créé avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="access_token", 
 *                 type="string", 
 *                 example="1|A8hD9fK2mN5pQ7rT3vW6xY0zB4cE1gH8jL"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Validation error")
 *         )
 *     ),
 * 
 * @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Server error")
 *         )
 *     )
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
                    'role_id' => $validated['role_id'],
                ]);

            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token                
            ], CREATED);
    }




 /**
 * @OA\Post(
 *     path="/login",
 *     summary="Se connecter",
 *          tags={"Authentication"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"login","password"},
 *             @OA\Property(property="login", type="string", example="paulpol"),
 *             @OA\Property(property="password", type="string", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur connecté avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="auth_token", type="string", example="A|NTtJ7YfygcDuXhGNqo2wWam1w0PJJnl1WlcSDry5d343d45")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erreur de validation",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Validation error")
 *         )
 *     ),
 * @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Server error")
 *         )
 *     )
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
     * Display the specified resource.
     */


    /**
 * @OA\Post(
 *     path="/logout",
 *     summary="Déconnexion de l'utilisateur",
 *     tags={"Authentication"},
 *     security={{"sanctum":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Déconnexion réussie",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Successfully logged out")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated")
 *         )
 *     )
 * )
 */
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ], OK);
    }
}

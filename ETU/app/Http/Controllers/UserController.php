<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\LanguageResource;
use App\Models\User;
use App\Models\Language;
use Illuminate\Support\Facades\Validator;
use App\Repository\UserRepositoryInterface;
use App\Http\Requests\PasswordRequest;

class UserController extends Controller
{
   
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

   /**
 * @OA\Get(
 *     path="/user/{id}",
 *     summary="Afficher un utilisateur",
 *     description="Affiche les informations de l'utilisateur connecté",
 *     operationId="getUser",
 *     tags={"User"},
 *     security={{"sanctum":{}}},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'utilisateur",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Utilisateur récupéré avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="first_name", type="string", example="Jean"),
 *                 @OA\Property(property="last_name", type="string", example="Durant"),
 *                 @OA\Property(property="login", type="string", example="JDurant"),
 *                 @OA\Property(property="email", type="string", example="jdurant@gmail.com")
 *             )
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=403,
 *         description="Accès interdit"
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié"
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur interne du serveur"
 *     )
 * )
 */
    public function show($id)
    {
        $user = $this->userRepository->show($id);
        return new UserResource($user);
    }

   
/**
 * @OA\Put(
 *     path="/user/{id}",
 *     summary="Modifier son mot de passe",
 *     description="Modifie le mot de passe de l'utilisateur connecté",
 *     operationId="updateUser",
 *     tags={"User"},
 *     security={{"sanctum":{}}},
 *
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de l'utilisateur",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *
 *     @OA\RequestBody(
 *         required=true,
 *         description="Nouveau mot de passe",
 *         @OA\JsonContent(
 *             required={"oldpassword", "newpassword", "newpassword_confirmation"},
 *             @OA\Property(property="oldpassword", type="string", maxLength=255),
 *             @OA\Property(property="newpassword", type="string", maxLength=255),
 *             @OA\Property(property="newpassword_confirmation", type="string", maxLength=255)
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=200,
 *         description="Mot de passe modifié avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Mot de passe modifié avec succès")
 *         )
 *     ),
 *
 *     @OA\Response(
 *         response=422,
 *         description="Données invalides"
 *     ),
 *
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié"
 *     ),
 *
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur"
 *     )
 * )
 */
    public function update(PasswordRequest $request, $id)
    {
        $validatedData = $request->validated();

        $user = $this->userRepository->show($id);
        $user->password = bcrypt($validatedData['newpassword']);
        $this->userRepository->update($id, $user->toArray());

    }
}

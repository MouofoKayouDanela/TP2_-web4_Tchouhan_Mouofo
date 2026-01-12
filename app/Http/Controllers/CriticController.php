<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Critic;
use App\Http\Resources\CriticResource;
use App\Http\Requests\CriticRequest;
use App\Repository\CriticRepositoryInterface;

class CriticController extends Controller
{
   

    private CriticRepositoryInterface $criticRepository;

    public function __construct(CriticRepositoryInterface $criticRepository)
    {
        $this->criticRepository = $criticRepository;
    }



/**
 * @OA\Post(
 *     path="/critic",
 *     summary="Créer une nouvelle critique",
 *     description="Crée une nouvelle critique dans la base de données",
 *     operationId="createCritic",
 *     tags={"Critic"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Données de la critique à créer",
 *         @OA\JsonContent(
 *             required={"user_id", "film_id", "score", "comment"},
 *             @OA\Property(property="user_id", type="integer", example=1, description="ID de l'utilisateur"),
 *             @OA\Property(property="film_id", type="integer", example=1, description="ID du film"),
 *             @OA\Property(property="score", type="number", format="float", minimum=0, example=4.5, description="Score attribué au film"),
 *             @OA\Property(property="comment", type="string", example="Film intéressant avec un bon suspense", description="Commentaire de l'utilisateur")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Critique créée avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Critique créée avec succès"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="user_id", type="integer", example=1),
 *                 @OA\Property(property="film_id", type="integer", example=1),
 *                 @OA\Property(property="score", type="number", format="float", example=4.5),
 *                 @OA\Property(property="comment", type="string", example="Film intéressant avec un bon suspense")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Données invalides",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Erreur de validation"),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(property="score", type="array", @OA\Items(type="string", example="Le champ score est requis"))
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Non authentifié"))
 *     ),
 *  @OA\Response(
 *         response=409,
 *         description="Critique déjà existante",
 *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Vous avez déjà critiqué ce film."))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erreur serveur",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Erreur interne du serveur")
 *         )
 *     )
 * )
 */



    public function store(CriticRequest $request)
    {
        $validatedData = $request->validated();

        $criticExistante = $this->criticRepository->verifierCritiqueExistante($validatedData['user_id'], $validatedData['film_id']);

        if ($criticExistante) {
            return response()->json([
                'message' => 'Vous avez déjà critiqué ce film.'
            ], CONFLICT);
        }

        $critic = $this->criticRepository->create($validatedData);      

         return response()->json([
                'nouvelle_critique' => new CriticResource($critic)                
            ], CREATED);
    }



}

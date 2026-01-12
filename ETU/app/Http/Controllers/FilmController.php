<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\FilmResource;
use App\Models\Film;
use App\Http\Requests\FilmRequest;
use App\Repository\FilmRepositoryInterface;


class FilmController extends Controller
{   
    private FilmRepositoryInterface $filmRepository;

    public function __construct(FilmRepositoryInterface $filmRepository)
    {
        $this->filmRepository = $filmRepository;
    }


/**
 * @OA\Post(
 *     path="/film",
 *     summary="Créer un nouveau film",
 *     operationId="createFilm",
 *     tags={"Film"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title","release_year","length","description","rating","language_id"},
 *             @OA\Property(property="title", type="string", maxLength=50, example="Inception"),
 *             @OA\Property(property="release_year", type="integer", minimum=1888, maximum=2100, example=2010),
 *             @OA\Property(property="length", type="integer", minimum=1, example=148),
 *             @OA\Property(property="description", type="string", example="Un voleur qui s'introduit dans les rêves"),
 *             @OA\Property(property="rating", type="string", enum={"G","PG","PG-13","R","NC-17"}, example="PG-13"),
 *             @OA\Property(property="special_features", type="string", example="Trailers"),
 *             @OA\Property(property="image", type="string", example="films/inception.jpg"),
 *             @OA\Property(property="language_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(response=201, description="Film créé avec succès"),
 *     @OA\Response(response=401, description="Non authentifié"),
 *     @OA\Response(response=422, description="Erreur de validation"),
 *     @OA\Response(response=500, description="Erreur serveur")
 * )
 */



    public function store(FilmRequest $request)
    {
        $validatedData = $request->validated();

        $film = $this->filmRepository->create($validatedData);      

         return response()->json([
                'nouveau_film' => new FilmResource($film)                
            ], CREATED);
    }




/**
 * @OA\Put(
 *     path="/film/{id}",
 *     summary="Modifier un film",
 *     description="Modifie un film déjà en base de données",
 *     operationId="updateFilm",
 *     tags={"Film"},
 *     security={{"sanctum":{}}},
 *    
 *     @OA\Parameter(
*     name="id",
*     in="path",
*     required=true,
*     description="ID du film à modifier",
*     @OA\Schema(type="integer", example=1)
*     ), 
 * 
 *      @OA\RequestBody(
 *         required=true,
 *         description="Données du film à modifier",
 *         @OA\JsonContent(
 *             required={"title", "release_year", "length", "description", "rating", "language_id"},
 *             @OA\Property(
 *                 property="title", 
 *                 type="string", 
 *                 maxLength=255,
 *                 example="Inception",
 *                 description="Titre du film"
 *             ),
 *             @OA\Property(
 *                 property="release_year", 
 *                 type="integer",
 *                 minimum=1888,
 *                 maximum=2100,
 *                 example=2010,
 *                 description="Année de sortie du film"
 *             ),
 *             @OA\Property(
 *                 property="length", 
 *                 type="integer",
 *                 minimum=1,
 *                 example=148,
 *                 description="Durée du film en minutes"
 *             ),
 *             @OA\Property(
 *                 property="description", 
 *                 type="string",
 *                 example="Un voleur qui s'introduit dans les rêves des gens",
 *                 description="Description du film"
 *             ),
 *             @OA\Property(
 *                 property="rating", 
 *                 type="string",
 *                 enum={"G", "PG", "PG-13", "R", "NC-17"},
 *                 example="PG-13",
 *                 description="Classification du film"
 *             ),
 *             @OA\Property(
 *                 property="special_features",                  
 *                     type="string",                     
 *                 example="Trailers",
 *                 description="Fonctionnalités spéciales disponibles"
 *             ),
 *             @OA\Property(
 *                 property="image", 
 *                 type="string",
 *                 example="films/inception.jpg",
 *                 description="Chemin de l'image du film"
 *             ),
 *             @OA\Property(
 *                 property="language_id", 
 *                 type="integer",
 *                 example=1,
 *                 description="ID de la langue du film"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Film modifié avec succès",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Film créé avec succès"),
 *             @OA\Property(
 *                 property="data",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Inception"),
 *                 @OA\Property(property="release_year", type="integer", example=2010),
 *                 @OA\Property(property="length", type="integer", example=148),
 *                 @OA\Property(property="description", type="string"),
 *                 @OA\Property(property="rating", type="string", example="PG-13"),
 *                 @OA\Property(property="special_features", type="array", @OA\Items(type="string")),
 *                 @OA\Property(property="image", type="string", example="films/inception.jpg"),
 *                 @OA\Property(property="language_id", type="integer", example=1),
 *                 @OA\Property(property="created_at", type="string", format="date-time"),
 *                 @OA\Property(property="updated_at", type="string", format="date-time")
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
 *                 @OA\Property(
 *                     property="title",
 *                     type="array",
 *                     @OA\Items(type="string", example="Le champ title est requis")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Non authentifié")
 *         )
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


    public function update(FilmRequest $request, $id)
    {
        $validatedData = $request->validated();

        $film = $this->filmRepository->update($id, $validatedData);

         return response()->json([
                'nouveau_film' => new FilmResource($film)                
            ], OK);
    }




  /**
 * @OA\Delete(
 *     path="/film/{id}",
 *     summary="Suppression d'un film",
 *     tags={"Film"},
 *     security={{"sanctum":{}}},
 *     
 *       @OA\Parameter(
*     name="id",
*     in="path",
*     required=true,
*     description="ID du film à supprimer",
*     @OA\Schema(type="integer", example=1)
*     ), 
 * 
 * 
 *     @OA\Response(
 *         response=200,
 *         description="Suppression réussie",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Successfully Deleted")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Non authentifié",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated")
 *         )
 *     ),
 * 
 *     @OA\Response(
 *      response=404,
 *      description="Film non trouvé",
 *      @OA\JsonContent(
 *      @OA\Property(property="message", type="string", example="Film not found")
 *     )
*   )
 * )
 */



    public function destroy($id)
    {
        $this->filmRepository->delete($id);

         return response()->json([
            'message' => 'Successfully deleted'
        ], NO_CONTENT);
    }
}


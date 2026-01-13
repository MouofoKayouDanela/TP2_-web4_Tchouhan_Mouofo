<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

//HTTP Codes (rajouter ceux qui manque)
define('OK', 200);
define('CREATED', 201);
define('NO_CONTENT', 204);
define('NOT_FOUND', 404);
define('INVALID_DATA', 422);
define('SERVER_ERROR', 500);
define('UNAUTHORIZED', 401);
define('FORBIDDEN', 403);
define('TOO_MANY_REQUESTS', 429);
define('CONFLICT', 409);


//Pagination
define('SEARCH_PAGINATION', 20);

//Roles
define('USER', 1);
define('ADMIN', 2);


/**
 * @OA\Info(
 *     title="Auth API",
 *     version="1.0.0",
 *     description="Documentation de l'authentification de l'API pour la gestion des films",
 *     @OA\Contact(
 *         email="filmMaximilien@films.com",
 *         name="Maximilien FILMS",
 *         url="http://www.films.com"
 *     )
 * )
 * 
 *  * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token"
 * )
 * 
 * 
 * @OA\Server(
 *     url="https://tp2-web4-tchouhan-mouofo-main-b6sher.laravel.cloud/api",
 *     description="Serveur de production"
 * )
 */


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

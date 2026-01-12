<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;


class OwnUserOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next)
    {
        $authId = auth()->user()->id;
        $routeUser = $request->route('id');

       
        $routeUserId = $routeUser;

        $user = \App\Models\User::find($routeUserId);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        

       if ((int)$authId !== (int)$routeUserId) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }

}

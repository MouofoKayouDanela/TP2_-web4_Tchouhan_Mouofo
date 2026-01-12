<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;




/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     required={"name", "email", "login"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID unique de l'utilisateur",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Nom complet de l'utilisateur",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Adresse email de l'utilisateur",
 *         example="john.doe@monsite.com"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Date de création du compte",
 *         example="2024-01-15T10:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Date de dernière modification",
 *         example="2024-12-01T14:20:00Z"
 *     ),
 *     @OA\Property(
 *         property="login",
 *         type="string",
 *         description="Nom d'utilisateur pour la connexion",
 *         example="johndoe123"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         description="Nom de famille",
 *         example="Doe"
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         description="Prénom",
 *         example="John"
 *     ),
 *  @OA\Property(
 *         property="role_id",
 *         type="integer",
 *         description="ID du rôle de l'utilisateur",
 *         example=2
 *     )
 * )
 */


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'password',
        'email',
        'last_name',
        'first_name',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function critics()
    {
        return $this->hasMany('App\Models\Critic');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

}

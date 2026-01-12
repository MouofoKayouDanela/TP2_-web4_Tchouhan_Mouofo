<?php

namespace App\Repository;

use App\Repository\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

interface CriticRepositoryInterface extends RepositoryInterface
{
    public function verifierCritiqueExistante(int $userId, int $filmId): ?Model;
}

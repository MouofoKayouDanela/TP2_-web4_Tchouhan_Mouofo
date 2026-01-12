<?php

namespace App\Repository\Eloquent;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\CriticRepositoryInterface;
use App\Models\Critic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class CriticRepository extends BaseRepository implements CriticRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Critic::class);
    }

   
   
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }  
    
      
    public function update(int $id, array $data): Model
    {
        $critic = $this->model->findOrFail($id);

        $critic->update($data);

        return $critic;
    }
    

    public function delete(int $id): bool
    {
        $critic = $this->model->findOrFail($id);
        return (bool) $critic->delete();
    }


    public function show(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function shows(): Collection
    {
        return $this->model->all();
    }

    public function verifierCritiqueExistante(int $userId, int $filmId): ?Model
    {
        return $this->model->where('user_id', $userId)
                           ->where('film_id', $filmId)
                           ->first();
    }

}












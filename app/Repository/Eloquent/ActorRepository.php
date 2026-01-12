<?php

namespace App\Repository\Eloquent;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\ActorRepositoryInterface;
use App\Models\Actor;
use Illuminate\Database\Eloquent\Model;

class ActorRepository extends BaseRepository implements ActorRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Actor::class);
    }

    
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }  
    
      
    public function update(int $id, array $data): Model
    {
        $actor = $this->model->findOrFail($id);

        $actor->update($data);

        return $actor;
    }
}
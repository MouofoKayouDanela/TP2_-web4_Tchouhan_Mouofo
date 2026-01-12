<?php

namespace App\Repository\Eloquent;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(User::class);
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
}












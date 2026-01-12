<?php

namespace App\Repository\Eloquent;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\FilmRepositoryInterface;
use App\Models\Film;
use Illuminate\Database\Eloquent\Model;

class FilmRepository extends BaseRepository implements FilmRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Film::class);
    }

    
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }  
    
      
    public function update(int $id, array $data): Model
    {
        $film = $this->model->findOrFail($id);

        $film->update($data);

        return $film;
    }
    

    public function delete(int $id): bool
    {
        $film = $this->model->findOrFail($id);
        return (bool) $film->delete();
    }
}

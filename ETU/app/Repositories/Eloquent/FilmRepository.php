<?php
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\FilmRepositoryInterface;
use App\Models\Film;

class FilmRepository extends BaseRepository implements FilmRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Film::class);
    }

 
    public function update(int $id, array $data)
    {
        $film = $this->model->findOrFail($id);

        $film->update([
             'name' => $data['name'],
             'release_year'=> $data['release_year'],
             'length'=> $data['length'],
             'description' => $data['description'],
             'rating' => $data['rating'],
             'language_id' => $data['language_id'],
             'special_features'=> $data['special_features'],
             'image'=> $data['image']
        ]);

        return $film;
    }
}
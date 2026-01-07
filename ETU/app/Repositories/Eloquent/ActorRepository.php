<?php
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\ActorRepositoryInterface;
use App\Models\Actor;

class ActorRepository extends BaseRepository implements ActorRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Actor::class);
    }

 
    public function update(int $id, array $data)
    {
        $actor = $this->model->findOrFail($id);

        $actor>update([
            'last_name' => $data['last_name'], 
            'first_name' => $data['first_name'],
            'birthdate' => $data['birthdate']
            
        ]);

        return $actor;
    }
}
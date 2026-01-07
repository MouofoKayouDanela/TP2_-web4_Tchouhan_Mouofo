<?php
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\CriticRepositoryInterface;
use App\Models\Critic;

class CriticRepository extends BaseRepository implements CriticRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Critic::class);
    }

 
    public function update(int $id, array $data)
    {
        $critic = $this->model->findOrFail($id);

        $critic->update([
            'score'=> $data['score'],
            'comment' => $data['comment'],
        ]);

        return $critic;
    }
}
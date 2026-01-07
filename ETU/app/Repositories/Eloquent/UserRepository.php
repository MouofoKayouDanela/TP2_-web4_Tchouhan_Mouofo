<?php
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(User::class);
    }

 
    public function update(int $id, array $data)
    {
        $user= $this->model->findOrFail($id);

        $user->update([
             'first_name' => $data['first_name'],
             'last_name'=> $data['last_name'],
             'login'=> $data['login'],
             'email' => $data['email'],
             'password' => $data['password']
        ]);

        return $user;
    }
}
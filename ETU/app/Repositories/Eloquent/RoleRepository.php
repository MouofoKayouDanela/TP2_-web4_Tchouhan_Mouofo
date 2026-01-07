<?php
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Models\Role;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Role::class);
    }

 
    public function update(int $id, array $data)
    {
        $role = $this->model->findOrFail($id);

        $role->update([
            'name' => $data['name'],
        ]);

        return $role;
    }
}
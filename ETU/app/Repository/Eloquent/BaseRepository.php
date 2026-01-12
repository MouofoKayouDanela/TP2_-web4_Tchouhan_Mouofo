<?php

namespace App\Repository\Eloquent;

use App\Repository\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    public function __construct(string $modelClass)
    {
        $this->model = new $modelClass;
    }

    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function getAll(int $perPage = 0): Collection|LengthAwarePaginator
    {
        return $perPage > 0
            ? $this->model->paginate($perPage)
            : $this->model->all();
    }

    public function update(int $id, array $data): ?Model
    {
        $item = $this->model->findOrFail($id);
        $item->update($data);
        return $item;
    }

    public function delete(int $id): bool
    {
        $item = $this->model->findOrFail($id);
        return (bool) $item->delete();
    }
}

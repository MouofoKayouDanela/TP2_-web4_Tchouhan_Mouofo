<?php

namespace App\Repositories\Interfaces;


use Illuminate\Support\Collection;

interface RepositoryInterface {

    public function getAll($perPage=0);
    public function find(int $id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}

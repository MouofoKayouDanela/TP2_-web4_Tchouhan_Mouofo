<?php

namespace App\Repository\Eloquent;

use App\Repository\Eloquent\BaseRepository;
use App\Repository\LanguageRepositoryInterface;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;

class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Language::class);
    }

    
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }  
    
      
    public function update(int $id, array $data): Model
    {
        $language = $this->model->findOrFail($id);

        $language->update($data);

        return $language;
    }
    

    public function delete(int $id): bool
    {
        $language = $this->model->findOrFail($id);
        return (bool) $language->delete();
    }
}

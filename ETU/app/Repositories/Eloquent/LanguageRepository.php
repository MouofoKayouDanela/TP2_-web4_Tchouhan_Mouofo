<?php
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Interfaces\LanguageRepositoryInterface;
use App\Models\Language;

class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Language::class);
    }

 
    public function update(int $id, array $data)
    {
        $language = $this->model->findOrFail($id);

        $language->update([
            'name' => $data['name'],
        ]);

        return $language;
    }
}
<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogPostRepository.
 */
class BlogPostRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Отримати список статей з пагінацією, пошуком та сортуванням
     *
     * @param int $perPage
     * @param string|null $search
     * @param string $sortBy
     * @param string $sortOrder
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = 5, $search = null, $sortBy = 'id', $sortOrder = 'desc')
    {
        $columns = ['id', 'title', 'slug', 'is_published', 'published_at', 'user_id', 'category_id', 'content_raw'];

        $allowedColumns = ['id', 'title', 'published_at'];
        $sortBy = in_array($sortBy, $allowedColumns) ? $sortBy : 'id';
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

        $query = $this->startConditions()
            ->select($columns)
            ->orderBy($sortBy, $sortOrder)
            ->with([
                'category' => function ($query) {
                    $query->select(['id', 'title']);
                },
                'user:id,name',
            ]);

        if (!empty($search)) {
            $query->where('title', 'LIKE', $search . '%');
        }

        return $query->paginate($perPage);
    }

    /**
     * Отримати model для редагування в адмінці
     * @param int $id
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
}

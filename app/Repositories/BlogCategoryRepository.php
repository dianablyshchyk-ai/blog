<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class BlogCategoryRepository.
 */
class BlogCategoryRepository extends CoreRepository
{
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Отримати модель для редагування в адмінці
     *
     * @param int $id
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Отримати список категорій для виводу в випадаючий список
     *
     * @return Collection
     */
    public function getForComboBox()
    {
        $columns = implode(', ', [
            'id',
            'CONCAT (id, ". ", title) AS id_title',
        ]);

        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            ->toBase()
            ->get();

        return $result;
    }

    /**
     * Отримати категорію для виводу пагінатором з пошуком та динамічним сортуванням
     *
     * @param int $perPage
     * @param string|null $search
     * @param string $sortBy
     * @param string $sortOrder
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = 5, $search = null, $sortBy = 'id', $sortOrder = 'desc')
    {
        $columns = [
            'id',
            'title',
            'parent_id',
            'slug',
            'description',
        ];

        $allowedColumns = ['id', 'title', 'slug'];
        $sortBy = in_array($sortBy, $allowedColumns) ? $sortBy : 'id';
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';

        $query = $this
            ->startConditions()
            ->select($columns)
            ->with(['parentCategory:id,title'])
            ->orderBy($sortBy, $sortOrder);
        if (!empty($search)) {
            $query->where('title', 'LIKE', $search . '%');
        }

        return $query->paginate($perPage);
    }
}

<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Support\Str;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;

class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd(__METHOD__);

        //$paginator = BlogCategory::paginate(5);
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);

        return $paginator;
    }

    /**
     * Store a newly created resource.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        $item = (new BlogCategory())->create($data);

        if ($item) {
            return [
                'success' => true,
                'message' => 'Успішно збережено',
            ];
        } else {
            return [
                'message' => 'Помилка збереження',
            ];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource.
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"])
                ->withInput();
        }

        $data = $request->all();

        $result = $item->update($data);

        if ($result) {
            return [
                'success' => 'Успішно збережено'
            ];
        } else {
            return [
                'msg' => 'Помилка збереження'
            ];
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        //
    }
}
